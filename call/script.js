document.addEventListener('DOMContentLoaded', () => {
  // Get the necessary HTML elements
  const videoElement = document.getElementById('video');
  const cameraButton = document.getElementById('cameraButton');
  const joinButton = document.getElementById('joinButton');
  const leaveButton = document.getElementById('leaveButton');

  // Variables to track camera status and call status
  let isCameraOn = false;
  let isInCall = false;

  // Function to toggle camera on/off
  function toggleCamera() {
    if (!isCameraOn) {
      // Turn on camera
      navigator.mediaDevices.getUserMedia({ video: true })
        .then(stream => {
          videoElement.srcObject = stream;
          isCameraOn = true;
          cameraButton.textContent = 'Turn Off Camera';
        })
        .catch(error => {
          console.log('Error accessing camera:', error);
        });
    } else {
      // Turn off camera
      const stream = videoElement.srcObject;
      const tracks = stream.getTracks();
      tracks.forEach(track => track.stop());
      videoElement.srcObject = null;
      isCameraOn = false;
      cameraButton.textContent = 'Turn On Camera';
    }
  }

  // Function to handle joining a call
  function joinCall() {
    // Perform necessary actions to join the call
    // For example, emit a 'join room' event to the server with the appropriate room ID
    // Handle the server response and update the UI accordingly
    isInCall = true;
    joinButton.disabled = true;
    leaveButton.disabled = false;
  }

  // Function to handle leaving a call
  function leaveCall() {
    // Perform necessary actions to leave the call
    // For example, emit a 'disconnect' event to the server
    // Handle the server response and update the UI accordingly
    isInCall = false;
    joinButton.disabled = false;
    leaveButton.disabled = true;
  }

  // Check if the necessary elements are found before adding the event listeners
  if (cameraButton) {
    cameraButton.addEventListener('click', toggleCamera);
  }
  if (joinButton) {
    joinButton.addEventListener('click', joinCall);
  }
  if (leaveButton) {
    leaveButton.addEventListener('click', leaveCall);
  }
});