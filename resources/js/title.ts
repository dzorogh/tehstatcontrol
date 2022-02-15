let suffix = '';

export function setTitle(title: string, replace?: boolean) {
  if (replace) {
    document.title = title;
  } else {
    document.title = title + suffix;
  }
}

export function setSuffix(newSuffix: string) {
  suffix = newSuffix;
}

