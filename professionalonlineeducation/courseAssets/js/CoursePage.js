$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// var minutesLabel = document.getElementById("minutes");
// var secondsLabel = document.getElementById("seconds");
// var totalSeconds = $("#previousMin").val();
//
// setInterval(setTime, 1000);
//
// function setTime() {
//     totalSeconds++;
//     secondsLabel.innerHTML = pad(totalSeconds % 60);
//     minutesLabel.innerHTML = pad(parseInt(totalSeconds / 60));
// }
// function pad(val) {
//     var valString = val + "";
//     if (valString.length < 2) {
//         return "0" + valString;
//     } else {
//         return valString;
//     }
// }

// let lesson_id = $("#lesson_id").val();
// let chapter_id = $("#chapter_id").val();
// let addTimeUrl = $("#addTime").val();
// $("#completeCourse").click(function(e){
//     e.preventDefault();
//     $.blockUI({ css: {
//             border: 'none',
//             padding: '15px',
//             backgroundColor: '#000',
//             '-webkit-border-radius': '10px',
//             '-moz-border-radius': '10px',
//             opacity: .5,
//             color: '#fff'
//         } });
//
//     let min = $("#minutes").html();
//     let sec = $("#seconds").html();
//     let ele = $(this);
//
//     $.ajax({
//         url: addTimeUrl,
//         method: 'POST',
//         data: {
//             lesson_id:lesson_id,
//             chapter_id:chapter_id,
//             min:min,
//             sec:sec
//         },
//         success: function (res) {
//             location.assign(ele.attr("href"));
//         }
//     });
// });

// $("#saveAndExit").click(function(){
//
//     $.blockUI({ css: {
//             border: 'none',
//             padding: '15px',
//             backgroundColor: '#000',
//             '-webkit-border-radius': '10px',
//             '-moz-border-radius': '10px',
//             opacity: .5,
//             color: '#fff'
//         } });
//
//
//     let min = $("#minutes").html();
//     let sec = $("#seconds").html();
//     $.ajax({
//         url: addTimeUrl,
//         method: 'POST',
//         data: {
//             lesson_id:lesson_id,
//             chapter_id:chapter_id,
//             min:min,
//             sec:sec
//         },
//         success: function (res) {
//             location.assign($("#saveAndExit").attr("data-exitTo"));
//         }
//     });
// });
