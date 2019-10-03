window.onload = function () {
    const slider = document.querySelector('#board');
    let isDown = false;
    let startX;
    let scrollLeft;

    slider.addEventListener('mousedown', (e) => {
        if (slider == event.currentTarget &&  e.target.className == "list-wrapper") {
            isDown = true;
        }
        startX = e.pageX - slider.offsetLeft;
        scrollLeft = slider.scrollLeft;
    });
    slider.addEventListener('mouseleave', (e) => {
        if (slider == event.currentTarget &&  e.target.className == "list-wrapper") {
            isDown = false;
        }
    });
    slider.addEventListener('mouseup', (e) => {
        if (slider == event.currentTarget &&  e.target.className == "list-wrapper") {
            isDown = false;
        }
    });
    slider.addEventListener('mousemove', (e) => {
        if (!isDown) return;
        // if (slider !== event.target &&) return;
        // e.preventDefault();
        const x = e.pageX - slider.offsetLeft;
        const walk = (x - startX) * 2; //scroll-fast
        slider.scrollLeft = scrollLeft - walk;
    });
}

// import 'jquery'

// $(function(){
//     var curDown = false,
//         curYPos = 0,
//         curXPos = 0;
//     $('#board').mousemove(function(m){
//         if(curDown === true){
//              // $('#board').scrollTop( $('#board').scrollTop() + (curYPos - m.pageY));
//              // $('#board').scrollLeft( $('#board').scrollLeft() + (curXPos - m.pageX));
//             const x = e.pageX - $('#board').scrollLeft();
//             const walk = (x - startX) * 2; //scroll-fast
//
//             $('#board').scrollLeft(($('#board').scrollLeft() - (curXPos - m.pageX)) / 2);
// //         slider.scrollLeft = scrollLeft - walk;
//         }
//         console.log($('#board').scrollLeft())
//     });
//
//     $('#board').mousedown(function(m){
//         console.log(m)
//         curDown = true;
//         curYPos = m.pageY;
//         curXPos = m.pageX;
//     });
//
//     $('#board').mouseup(function(){
//         curDown = false;
//     });
// })
