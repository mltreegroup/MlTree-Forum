var runList = {
    Run: [],
    RunEnd: [],
};

function addRunEvent(func) {
    runList.Run.push(func);
};

function addRunEndEvent(func) {
    runList.RunEnd.push(func)
};



$(document).ready(function () {
    runList.Run.forEach((item, index) => {
        item();
    });
});
Window.onload = function () {
    runList.RunEnd.forEach((item, index) => {
        item();
    });
}