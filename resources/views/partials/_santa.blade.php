<style>
img.santa {
  position: absolute;
  animation: moveImage 15s linear infinite;
  left: -300px;
}

@keyframes moveImage {
    100% {
      transform: translateX(calc(150vw + 300px));
    }
}
</style>
<img class="santa" src="/assets/img/santa.png" width="20%" />	