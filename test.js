const cssVarToRgb = (varName) => {
    // creating dummy div
    const div = document.createElement('div');
    div.style.color = `var(${varName})`;
    div.style.display = 'none';
    document.body.appendChild(div);
    const color = getComputedStyle(div).color;
    document.body.removeChild(div);
    return color;
};
// This converts "light-dark()" to actual rgb depending on active scheme.
