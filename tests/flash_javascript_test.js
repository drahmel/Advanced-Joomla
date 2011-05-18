Recorder.removeEventHandler('click');
Recorder.addEventHandler('clickAt', 'click', function(event) {
var x = event.clientX -editor.seleniumAPI.Selenium.prototype.getElementPositionLeft( event.target);
var y = event.clientY - editor.seleniumAPI.Selenium.prototype.getElementPositionTop(event.target);
this.record('clickAt', this.findLocator(event.target), x + ',' + y);
}, { capture: true });

