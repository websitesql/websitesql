class WsqlDateTimePicker {
    constructor(targetElement) {
      if (!targetElement) {
        throw new Error("A valid DOM element must be provided.");
      }
  
      this.targetElement = targetElement;
      this.pickerElement = null;
      this.initPicker();
    }
  
    initPicker() {
      this.pickerElement = document.createElement('div');
      this.pickerElement.className = `fixed z-50 bg-white border border-gray-300 shadow-lg rounded-lg p-4 w-80 space-y-4`;
  
      const dateContainer = document.createElement('div');
      const dateLabel = document.createElement('label');
      dateLabel.className = "block text-sm font-medium text-gray-700";
      dateLabel.textContent = "Select Date";
      dateContainer.appendChild(dateLabel);
  
      const dateInput = document.createElement('input');
      dateInput.type = "date";
      dateInput.id = "date-input";
      dateInput.className = "mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500";
      dateContainer.appendChild(dateInput);
  
      const timeContainer = document.createElement('div');
      const timeLabel = document.createElement('label');
      timeLabel.className = "block text-sm font-medium text-gray-700";
      timeLabel.textContent = "Select Time";
      timeContainer.appendChild(timeLabel);
  
      const timeSelectors = document.createElement('div');
      timeSelectors.className = "flex space-x-2 mt-1";
  
      const hourInput = document.createElement('input');
      hourInput.type = "number";
      hourInput.id = "hour-input";
      hourInput.min = 0;
      hourInput.max = 23;
      hourInput.placeholder = "HH";
      hourInput.className = "w-16 px-2 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500";
      timeSelectors.appendChild(hourInput);
  
      const minuteInput = document.createElement('input');
      minuteInput.type = "number";
      minuteInput.id = "minute-input";
      minuteInput.min = 0;
      minuteInput.max = 59;
      minuteInput.placeholder = "MM";
      minuteInput.className = "w-16 px-2 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500";
      timeSelectors.appendChild(minuteInput);
  
      const secondInput = document.createElement('input');
      secondInput.type = "number";
      secondInput.id = "second-input";
      secondInput.min = 0;
      secondInput.max = 59;
      secondInput.placeholder = "SS";
      secondInput.className = "w-16 px-2 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500";
      timeSelectors.appendChild(secondInput);
  
      timeContainer.appendChild(timeSelectors);
  
      const buttonContainer = document.createElement('div');
      buttonContainer.className = "flex justify-end space-x-2";
  
      const cancelButton = document.createElement('button');
      cancelButton.id = "cancel-btn";
      cancelButton.className = "px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300";
      cancelButton.textContent = "Cancel";
      buttonContainer.appendChild(cancelButton);
  
      const applyButton = document.createElement('button');
      applyButton.id = "apply-btn";
      applyButton.className = "px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600";
      applyButton.textContent = "Apply";
      buttonContainer.appendChild(applyButton);
  
      this.pickerElement.appendChild(dateContainer);
      this.pickerElement.appendChild(timeContainer);
      this.pickerElement.appendChild(buttonContainer);
  
      document.body.appendChild(this.pickerElement);
      this.positionPicker();
      this.addEventListeners();
    }
  
    positionPicker() {
      const rect = this.targetElement.getBoundingClientRect();
      this.pickerElement.style.top = `${rect.bottom + window.scrollY + 8}px`;
      this.pickerElement.style.left = `${rect.left + window.scrollX}px`;
    }
  
    addEventListeners() {
      const cancelBtn = this.pickerElement.querySelector('#cancel-btn');
      const applyBtn = this.pickerElement.querySelector('#apply-btn');
  
      cancelBtn.addEventListener('click', () => this.closePicker());
  
      applyBtn.addEventListener('click', () => {
        const dateInput = this.pickerElement.querySelector('#date-input').value;
        const hourInput = this.pickerElement.querySelector('#hour-input').value;
        const minuteInput = this.pickerElement.querySelector('#minute-input').value;
        const secondInput = this.pickerElement.querySelector('#second-input').value;
  
        if (dateInput && hourInput !== "" && minuteInput !== "" && secondInput !== "") {
          const selectedDateTime = `${dateInput} ${hourInput.padStart(2, '0')}:${minuteInput.padStart(2, '0')}:${secondInput.padStart(2, '0')}`;
          this.targetElement.value = selectedDateTime;
          this.closePicker();
        } else {
          alert('Please select date, hour, minute, and second.');
        }
      });
    }
  
    closePicker() {
      if (this.pickerElement) {
        this.pickerElement.remove();
        this.pickerElement = null;
      }
    }
  }
  
  // Usage Example:
  // Attach this class to a DOM element (e.g., an input field):
  // const datePicker = new WsqlDateTimePicker(document.getElementById('your-input-element-id'));
  