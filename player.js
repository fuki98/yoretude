//DEFINES

//video itself
const video = document.getElementById('video');

//radial buttons
const playButton = document.getElementById('playBtn');
const playIcon = document.getElementById('playIcon');
const pauseIcon = document.getElementById('pauseIcon');
const stopButton = document.getElementById('stopBtn');

//volume controls
const muteBtn = document.getElementById('muteBtn');
const muteSign = document.getElementById('muteSign');
const volSlider = document.getElementById('vlSlide');

//volume decorations
const volDecoFg = document.getElementById('volDecoFg');
const volDecoParent = document.getElementById('vlBtnDeco');
const volDecoMin = document.getElementById('vlBtnDeco1');
const volDecoMed = document.getElementById('vlBtnDeco2');
const volDecoMax = document.getElementById('vlBtnDeco3');

//time elapsed and duration
const elapsed = document.getElementById('elapsed');
const duration = document.getElementById('duration');

//seekbar
const seekProgress = document.getElementById('seekProgress');
const bufferProgress = document.getElementById('bufferProgress');
const seekHandle = document.getElementById('seekHandle');


//context menu
const contextMenu = document.getElementById('playerContextMenu');
const contextAbout = document.getElementById('contextAbout');
const contextMute = document.getElementById('contextMute');
const contextLoop = document.getElementById('contextLoop');
const muteTick = document.getElementById('muteTick');
const loopTick = document.getElementById('loopTick');

//end screen stuff
const endScreen = document.getElementById('endScreen');
const replayBtn = document.getElementById('endReplayBtn');

//the whole player
const scope = document.getElementById('playerBox');

//size controls
const shrinkBtn = document.getElementById('btnShrink');
const unshrinkBtn = document.getElementById('btnUnshrink');

//about box
const aboutBox = document.getElementById('aboutBox');
const aboutCloseBtn = document.getElementById('aboutCloseBtn');

var muted = 0;

//FUNCTIONS
//replace standart context menu and disallow standard right-click
scope.addEventListener("contextmenu", (event) => {
  event.preventDefault();

  const { clientX: mouseX, clientY: mouseY } = event;

  contextMenu.style.top = `${mouseY}px`;
  contextMenu.style.left = `${mouseX}px`;

  contextMenu.style.display = "block";
});

scope.addEventListener("click", (e) => {
  if (e.target.offsetParent != contextMenu) {
    contextMenu.style.display = "none";
  }
});

function updateBufferProgress() {
  var range = 0;
  var bf = this.buffered;
  var time = this.currentTime;

  while(!(bf.start(range) <= time && time <= bf.end(range))) {
      range += 1;
  }
  var loadStartPercentage = bf.start(range) / this.duration;
  var loadEndPercentage = bf.end(range) / this.duration;
  var loadPercentage = loadEndPercentage - loadStartPercentage;
  console.log(loadPercentage);
  bufferProgress.value = loadPercentage;
}

video.controls = false;

//play video (crude autoplay workaround)
video.load()
video.play()

function togglePlay() {
    if (video.paused || video.ended) {
      video.play();
      pauseIcon.style.display = "block";
      playIcon.style.display = "none";
      displayEndScreen();
    } else {
      video.pause();
      pauseIcon.style.display = "none";
      playIcon.style.display = "block";
    }
}

function formatTime(timeInSeconds) {
    const result = new Date(timeInSeconds * 1000).toISOString().substr(11, 8);
  
    return {
      minutes: result.substr(3, 2),
      seconds: result.substr(6, 2),
    };
  };

function videoStop() {
    video.pause();
    video.currentTime = 0;
    pauseIcon.style.display = "none";
    playIcon.style.display = "block";
}

function initializeVideo() {
    const videoDuration = Math.round(video.duration);
    seekHandle.setAttribute('max', videoDuration);
    seekProgress.setAttribute('max', videoDuration);
    const time = formatTime(videoDuration);
    duration.innerText = `${time.minutes}:${time.seconds}`;
    duration.setAttribute('datetime', `${time.minutes}m ${time.seconds}s`)
    updateProgress();
}

function skipAhead(event) {
  const skipTo = event.target.dataset.seekHandle ? event.target.dataset.seekHandle : event.target.value;
  video.currentTime = skipTo;
  seekProgress.value = skipTo;
  seekHandle.value = skipTo;

  displayEndScreen();
}

function updateElapsed() {
    const time = formatTime(Math.round(video.currentTime));
    elapsed.innerText = `${time.minutes}:${time.seconds}`;
    elapsed.setAttribute('datetime', `${time.minutes}m ${time.seconds}s`)
}

function updateProgress() {
  seekHandle.value = Math.floor(video.currentTime);
  seekProgress.value = Math.floor(video.currentTime);
}

function detectAutoplay() {
    pauseIcon.style.display = "block";
    playIcon.style.display = "none";
}

//detect video end
function detectVideoEnd() {
    pauseIcon.style.display = "none";
    playIcon.style.display = "block";
    displayEndScreen();
}

//display and close about box
function displayAbout() {
  contextMenu.style.display = "none";
  aboutBox.style.display = "flex";
}

aboutCloseBtn.addEventListener('click', function() {
  aboutBox.style.display = "none";
})

//display end screen
function displayEndScreen() {
  if (!video.loop) {
    if (video.ended) {
      endScreen.classList.remove("hidden");
      seekHandle.value = 0;
    seekProgress.value = 0;
    }
    else {
      endScreen.classList.add("hidden");
    }
  }
}

//end screen replay button
function videoEndReplay() {
  video.currentTime = 0;
  video.play();
  displayEndScreen();
}

//shrink video toggle
function videoShrinkToggle() {

  video.classList.toggle("playerVideoAreaShrinked");
  shrinkBtn.classList.toggle("btnUnshrink");
}

//mute button in control panel
function videoMute() {
  video.muted = !video.muted;
  muteSign.classList.toggle("hidden");
  muteTick.classList.toggle("hidden");
  if (video.muted) {
    volSlider.setAttribute('data-volume', volSlider.value);
    volSlider.value = 0;
    volDecoFg.style.width = "0%"
  } else {
    volSlider.value = volSlider.dataset.volume;
    volDecoFg.style.width = (video.volume * 100) + '%';
  }
}

//mute option in context menu
function videoContextMute() {
  videoMute();
  contextMenu.style.display = "none";
}

//loop option, in same place
function videoContextLoop() {
  video.loop = !video.loop;
  loopTick.classList.toggle("hidden");
  contextMenu.style.display = "none";
}

function updateVolume() {
  if (video.muted) {
    video.muted = false;
    muteSign.classList.toggle("hidden");
    muteTick.classList.toggle("hidden");
  }
  video.volume = volSlider.value;
  volDecoFg.style.width = (video.volume * 100) + '%';
}

function updateVolDeco() {
  if (video.muted || video.volume == 0) {
    volDecoMin.classList.add("hidden");
    volDecoMed.classList.add("hidden");
    volDecoMax.classList.add("hidden");
  } else if (video.volume > 0 && video.volume <= 0.34) {
    volDecoMin.classList.remove("hidden");
    volDecoMed.classList.add("hidden");
    volDecoMax.classList.add("hidden");
  } else if (video.volume > 0.34 && video.volume <= 0.67) {
    volDecoMin.classList.remove("hidden");
    volDecoMed.classList.remove("hidden");
    volDecoMax.classList.add("hidden"); 
  } else {
    volDecoMin.classList.remove("hidden");
    volDecoMed.classList.remove("hidden");
    volDecoMax.classList.remove("hidden");
  }
}

//save current volume to localstorage
function rememberVolume() {
  if (!video.muted) {
    localStorage.setItem('volumedata', volSlider.value);
  }
  console.log('remembered volume level ' + localStorage.getItem('volumedata'));
}

//save if video muted or not to localstorage
function rememberMuteState() {
  if (video.muted) {
   localStorage.setItem('mutestate', 1);
  } else {
   localStorage.setItem('mutestate', 0);
  }
  console.log('remembered mute state ' + localStorage.getItem('mutestate'));
}

//pull saved volume and mute flag from localstorage
function recallVolumeMuteState() {
  if (localStorage.getItem('volumedata') !== null) {
    volSlider.value = localStorage.getItem('volumedata');
    updateVolume();
    updateVolDeco();
  }
  if (localStorage.getItem('mutestate') !== null && localStorage.getItem('mutestate') == 1) {
    videoMute();
  }
  console.log('recalled volume level ' + localStorage.getItem('volumedata') + ' and mute state ' + localStorage.getItem('mutestate'));
}

//LISTENERS
//inits
video.addEventListener('loadedmetadata', initializeVideo);

//updaters
video.addEventListener('timeupdate', updateElapsed);
video.addEventListener('timeupdate', updateProgress);
video.addEventListener('timeupdate', updateBufferProgress);
video.addEventListener('progress', updateBufferProgress);

//sensors
video.addEventListener('play', detectAutoplay);
video.addEventListener('ended', detectVideoEnd);

//playback control listeners
playButton.addEventListener('click', togglePlay);
stopButton.addEventListener('click', videoStop);
seekHandle.addEventListener('input', skipAhead);
replayBtn.addEventListener('click', videoEndReplay);

//volume control listeners
muteBtn.addEventListener('click', videoMute);
muteBtn.addEventListener('click', rememberMuteState);
volSlider.addEventListener('input', updateVolume);
volSlider.addEventListener('input', rememberVolume);  
video.addEventListener('volumechange', updateVolDeco)

//size control listeners
shrinkBtn.addEventListener('click', videoShrinkToggle);

//context menu listeners
contextAbout.addEventListener('click', displayAbout);
contextMute.addEventListener('click', videoContextMute);
contextLoop.addEventListener('click', videoContextLoop);

//localstorage operations
addEventListener("load", (event) => {
  recallVolumeMuteState();
});