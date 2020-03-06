import React, { useState } from 'react';
import ReactDOM from 'react-dom';


function Cell(onClick) {
    return (
        <button onClick={() => onClick()}/>
    )
}



function CellMatrix(props) {
    const [cells, setCells] = useState(1)//Array(9).fill(null)


    function HandleClick(now) {

        setCells(now+1)
    }

    
    function RenderCell(i) {
        return (
            Cell(()=>HandleClick(cells))
        )
    }

    return (
        <div>
            {RenderCell(1)}
            {RenderCell(2)}
        </div>
    )
}

const app = document.getElementById('app');
ReactDOM.render(<CellMatrix/>, app);










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