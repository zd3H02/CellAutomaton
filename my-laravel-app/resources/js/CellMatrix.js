import React, { useState, useRef, useEffect } from 'react';
import ReactDOM from 'react-dom';


const useFetch = (url, init={}) => {
    const [data, setData] = useState(null);

    async function fetchData() {
        const response = await fetch(url,init);
        const json = await response.json();
        // const json = await response;
        console.log(json)
        console.log(JSON.stringify({"num":1}))
        //setData(json);
    }

    useEffect(() => {fetchData()},[url]);
    return data;
};



function Cell(props) {
    return (
        <button onClick={() => props.onClick()}/>
    )
}




function CellMatrix(props) {
    const [cellColor, setCellColor] = useState(Array(9).fill('#000000'))
    const [rColor, setRColor] = useState(0)
    const test = new FormData()
    test.append('num', 1)
    
    const data = useFetch(
        '../local/stop'
        ,
        {
            method: "POST",
            //Headersを書き忘れるとエラーになる
            headers: {"X-CSRF-TOKEN": document.getElementById("csrf-token").content},
            credentials: "include",
            body:test
        }
    )

    // useEffect(
    //     () => (
    //         $(function(){
    //             setInterval(function(){
    //                 let a = $('.num').text();
    //                 $.ajax({
    //                     headers: {
    //                         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //                     },//Headersを書き忘れるとエラーになる
    //                     url:  '../local/stop',
    //                     type: 'POST',
    //                     data: {
    //                     'num' : 1
    //                     },
    //                     //通信状態に問題がないかどうか
    //                     success: function(array) {
    //                         console.log(array);
    //                         //console.log(JSON.parse(array));
    //                         $('.num').text(array);
    //                         console.log($('meta[name="csrf-token"]').attr('content'))
    //                         console.log( document.getElementById("csrf-token").content)
    //                     },
    //                     //通信エラーになった場合の処理
    //                     error: function(err) {
    //                         //エラー処理を書く
    //                     }
    //                 });
    //             },2000)
    //         })
    //     )
    // )

    function HandleClick(color) {
        const newCellColor = cellColor.slice();
        newCellColor[1] = color
        setCellColor(newCellColor)
    }

    function RenderCell(i) {
        return (
            <Cell onClick={()=>HandleClick(rColor)}/>
        )
    }


    return (
        <div>
            {RenderCell(1)}
            {RenderCell(2)}
            <ColorInput value={rColor} onChange={setRColor}/>
        </div>
    )
}


function ColorInput(props) {
    const rOptions = Array(255).fill(0)
    const rRef = useRef(null)

    rOptions.forEach((rOption, index) => (
        rOptions.push( <option value= {index} key={'r'+ index}>{index}</option> )
    ));

    function handleChange(event) {
        props.onChange(event.target.value)
        console.log(event.target.value)
    }

    return (
        <div>
            <p>R:
                <select ref={rRef} onChange={handleChange}>
                    {rOptions}
                </select>
            </p>
        </div>
    )
}



const app = document.getElementById('app');
ReactDOM.render(<CellMatrix/>, app);







// function colorInput() {
//     return (
//         <input type="text"/>
//     )
// }



// const jsxMatrix = matrix.map((row) =>
// <div key={row.toString()}>
//     {row.map((v) =>
//         <Cell key={v.toString()}/>
//     )}
// </div>
// )






// import React, { useState, memo } from "react";
// import ReactDOM from "react-dom";


// function App() {
//   const [isRender, setIsRender] = useState(0);
//   return (
//     <div className="App">
//       <div>{isRender}</div>
//       <button onClick={() => setIsRender(isRender + 1)}>increment</button>
//       <Test />
//     </div>
//   );
// }

// const Test = memo(() => {
//   console.log("Test");
//   return <div>Test</div>;
// });

// const rootElement = document.getElementById("root");
// ReactDOM.render(<App />, rootElement);



// import React, { useState, useRef } from 'react';
// import ReactDOM from 'react-dom';

// function Cell(props) {
//     const [x, setX] = useState(props.x)
//     const [y, sety] = useState(props.y)
//     const [color, setColor] = useState('#000000')

//     const items = useRef(Array.from({length: 10}, () => React.createRef()))
//     const testtest = useRef()

//     const matrix = [
//             [1, 2, 3, 4, 5],
//             [6, 7, 8, 9, 10]
//         ];

//     const omatrix = matrix.map((rows) =>
//         <div key={rows.toString()}>
//             {rows.map((v) =>
//                 <span key={v.toString()} ref={items[v]}>|{v}</span>
//             )}
//         </div>
//     )
    

//     return (
        
//         <div>
//             <div>{omatrix}</div>
//             <button onClick={() => setColor('ffffff')} style={{color}}>
//             {x}{y}
//             </button>
//             <p ref={testtest}>{x}</p>
//         </div>
//     )
// }




// function Cells() {
    
//     return (
//         <div>
//         <Cell x='23' y='1'/>
//         <Cell x='2' y='1'/>
//         </div>
//     )

// }

// const app = document.getElementById('app');
// ReactDOM.render(<Cells/>, app);