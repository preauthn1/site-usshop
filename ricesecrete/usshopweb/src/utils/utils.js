export function getImagePath(imageName) {
    return new URL(`../assets/images/${imageName}`, import.meta.url).href;
  }