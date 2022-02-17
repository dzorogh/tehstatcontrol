let suffix = '';

export function setTitle(title: string, replace?: boolean) {
  console.log('setTitle', title, replace ? 'replace' : 'add');
  
  if (replace) {
    document.title = title;
  } else {
    document.title = title + suffix;
  }
}

export function setSuffix(newSuffix: string) {
  console.log('setSuffix', newSuffix);
  
  suffix = newSuffix;
}

