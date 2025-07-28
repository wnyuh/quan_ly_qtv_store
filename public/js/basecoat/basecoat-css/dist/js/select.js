(() => {
  const initSelect = (selectComponent) => {
    const trigger = selectComponent.querySelector(':scope > button');
    const selectedLabel = trigger.querySelector(':scope > span');
    const popover = selectComponent.querySelector(':scope > [data-popover]');
    const listbox = popover.querySelector('[role="listbox"]');
    const input = selectComponent.querySelector(':scope > input[type="hidden"]');
    const filter = selectComponent.querySelector('header input[type="text"]');
    if (!trigger || !popover || !listbox || !input) {
      const missing = [];
      if (!trigger) missing.push('trigger');
      if (!popover) missing.push('popover');
      if (!listbox) missing.push('listbox');
      if (!input)   missing.push('input');
      console.error(`Select component initialisation failed. Missing element(s): ${missing.join(', ')}`, selectComponent);
      return;
    }
    
    const options = Array.from(listbox.querySelectorAll('[role="option"]'));
    let visibleOptions = [...options];
    let activeIndex = -1;

    const setActiveOption = (index) => {
      if (activeIndex > -1 && options[activeIndex]) {
        options[activeIndex].classList.remove('active');
      }
      
      activeIndex = index;
      
      if (activeIndex > -1) {
        const activeOption = options[activeIndex];
        activeOption.classList.add('active');
        if (activeOption.id) {
          trigger.setAttribute('aria-activedescendant', activeOption.id);
        } else {
          trigger.removeAttribute('aria-activedescendant');
        }
      } else {
        trigger.removeAttribute('aria-activedescendant');
      }
    };

    const hasTransition = () => {
      const style = getComputedStyle(popover);
      return parseFloat(style.transitionDuration) > 0 || parseFloat(style.transitionDelay) > 0;
    };

    const updateValue = (option) => {
      if (option) {
        selectedLabel.innerHTML = option.dataset.label || option.innerHTML;
        input.value = option.dataset.value;
        listbox.querySelector('[role="option"][aria-selected="true"]')?.removeAttribute('aria-selected');
        option.setAttribute('aria-selected', 'true');
      }
    };

    const closePopover = (focusOnTrigger = true) => {
      if (popover.getAttribute('aria-hidden') === 'true') return;
      
      if (filter) {
        const resetFilter = () => {
          filter.value = '';
          visibleOptions = [...options];
          options.forEach(opt => opt.setAttribute('aria-hidden', 'false'));
        };
        
        if (hasTransition()) {
          popover.addEventListener('transitionend', resetFilter, { once: true });
        } else {
          resetFilter();
        }
      }
      
      if (focusOnTrigger) trigger.focus();
      popover.setAttribute('aria-hidden', 'true');
      trigger.setAttribute('aria-expanded', 'false');
      setActiveOption(-1);
    }

    const selectOption = (option) => {
      if (!option) return;
      
      const oldValue = input.value;
      const newValue = option.dataset.value;

      if (newValue != null && newValue !== oldValue) {
        updateValue(option);
      }
      
      closePopover();
      
      if (newValue !== oldValue) {
        const event = new CustomEvent('change', {
          detail: { value: newValue },
          bubbles: true
        });
        selectComponent.dispatchEvent(event);
      }
    };

    const selectByValue = (value) => {
      const option = options.find(opt => opt.dataset.value === value);
      selectOption(option);
    };

    if (filter) {
      const filterOptions = () => {
        const searchTerm = filter.value.trim().toLowerCase();
        
        setActiveOption(-1);

        visibleOptions = [];
        options.forEach(option => {
          const optionText = (option.dataset.label || option.textContent).trim().toLowerCase();
          const matches = optionText.includes(searchTerm);
          option.setAttribute('aria-hidden', String(!matches));
          if (matches) {
            visibleOptions.push(option);
          }
        });
      };
  
      filter.addEventListener('input', filterOptions);
    }

    let initialOption = options.find(opt => opt.dataset.value === input.value);
    if (!initialOption && options.length > 0) initialOption = options[0];

    updateValue(initialOption);

    const handleKeyNavigation = (event) => {
      const isPopoverOpen = popover.getAttribute('aria-hidden') === 'false';

      if (!['ArrowDown', 'ArrowUp', 'Enter', 'Home', 'End', 'Escape'].includes(event.key)) {
        return;
      }

      if (!isPopoverOpen) {
        if (event.key !== 'Enter' && event.key !== 'Escape') {
          event.preventDefault();
          trigger.click();
        }
        return;
      }
      
      event.preventDefault();

      if (event.key === 'Escape') {
        closePopover();
        return;
      }
      
      if (event.key === 'Enter') {
        if (activeIndex > -1) {
          selectOption(options[activeIndex]);
        }
        return;
      }

      if (visibleOptions.length === 0) return;

      const currentVisibleIndex = activeIndex > -1 ? visibleOptions.indexOf(options[activeIndex]) : -1;
      let nextVisibleIndex = currentVisibleIndex;

      switch (event.key) {
        case 'ArrowDown':
          if (currentVisibleIndex < visibleOptions.length - 1) {
            nextVisibleIndex = currentVisibleIndex + 1;
          }
          break;
        case 'ArrowUp':
          if (currentVisibleIndex > 0) {
            nextVisibleIndex = currentVisibleIndex - 1;
          } else if (currentVisibleIndex === -1) {
            nextVisibleIndex = 0;
          }
          break;
        case 'Home':
          nextVisibleIndex = 0;
          break;
        case 'End':
          nextVisibleIndex = visibleOptions.length - 1;
          break;
      }

      if (nextVisibleIndex !== currentVisibleIndex) {
        const newActiveOption = visibleOptions[nextVisibleIndex];
        setActiveOption(options.indexOf(newActiveOption));
        newActiveOption.scrollIntoView({ block: 'nearest', behavior: 'smooth' });
      }
    };

    listbox.addEventListener('mousemove', (event) => {
      const option = event.target.closest('[role="option"]');
      if (option && visibleOptions.includes(option)) {
        const index = options.indexOf(option);
        if (index !== activeIndex) {
          setActiveOption(index);
        }
      }
    });

    listbox.addEventListener('mouseleave', () => {
      const selectedOption = listbox.querySelector('[role="option"][aria-selected="true"]');
      if (selectedOption) {
        setActiveOption(options.indexOf(selectedOption));
      } else {
        setActiveOption(-1);
      }
    });

    trigger.addEventListener('keydown', handleKeyNavigation);
    if (filter) {
      filter.addEventListener('keydown', handleKeyNavigation);
    }

    const openPopover = () => {
      document.dispatchEvent(new CustomEvent('basecoat:popover', {
        detail: { source: selectComponent }
      }));
      
      if (filter) {
        if (hasTransition()) {
          popover.addEventListener('transitionend', () => {
            filter.focus();
          }, { once: true });
        } else {
          filter.focus();
        }
      }

      popover.setAttribute('aria-hidden', 'false');
      trigger.setAttribute('aria-expanded', 'true');
      
      const selectedOption = listbox.querySelector('[role="option"][aria-selected="true"]');
      if (selectedOption) {
        setActiveOption(options.indexOf(selectedOption));
        selectedOption.scrollIntoView({ block: 'nearest' });
      }
    };

    trigger.addEventListener('click', () => {
      const isExpanded = trigger.getAttribute('aria-expanded') === 'true';
      if (isExpanded) {
        closePopover();
      } else {
        openPopover();
      }
    });

    listbox.addEventListener('click', (event) => {
      const clickedOption = event.target.closest('[role="option"]');
      if (clickedOption) {
        selectOption(clickedOption);
      }
    });

    document.addEventListener('click', (event) => {
      if (!selectComponent.contains(event.target)) {
        closePopover(false);
      }
    });

    document.addEventListener('basecoat:popover', (event) => {
      if (event.detail.source !== selectComponent) {
        closePopover(false);
      }
    });

    popover.setAttribute('aria-hidden', 'true');
    
    selectComponent.selectByValue = selectByValue;
    selectComponent.dataset.selectInitialized = true;
    selectComponent.dispatchEvent(new CustomEvent('basecoat:initialized'));
  };

  document.querySelectorAll('div.select:not([data-select-initialized])').forEach(initSelect);

  const observer = new MutationObserver((mutations) => {
    mutations.forEach((mutation) => {
      mutation.addedNodes.forEach((node) => {
        if (node.nodeType === Node.ELEMENT_NODE) {
          if (node.matches('div.select:not([data-select-initialized])')) {
            initSelect(node);
          }
          node.querySelectorAll('div.select:not([data-select-initialized])').forEach(initSelect);
        }
      });
    });
  });
  
  observer.observe(document.body, { childList: true, subtree: true });
})();