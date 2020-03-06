// $(function(){
//     setInterval(function(){
//         let a = $('.num').text();
//         $.ajax({
//             headers: {
//                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//             },//Headersを書き忘れるとエラーになる
//             url:  '../local',
//             type: 'GET',
//             data: {
//               'num' : a
//             },
//             //通信状態に問題がないかどうか
//             success: function(array) {
//                 console.log(array);
//                 // console.log(JSON.parse(array));
//                 $('.num').text(array);
//             },
//             //通信エラーになった場合の処理
//             error: function(err) {
//                 //エラー処理を書く
//             }
//         });
//     },2000);
// });

import React, { useState } from 'react';

function Example() {
  // Declare a new state variable, which we'll call "count"
  const [count, setCount] = useState(0);

  return (
    <div>
      <p>You clicked {count} times</p>
      <button onClick={() => setCount(count + 1)}>
        Click me
      </button>
    </div>
    <p className="num"></p>
  );
}
//test