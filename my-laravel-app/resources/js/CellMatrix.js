import React, { useState } from 'react';
import ReactDOM from 'react-dom';

function Cell(props) {
    const [i, setI] = useState(props.i)
    const [color, setColor] = useState('#000000')

    return (
        <button onClick={() => setColor('ffffff')} style={{color}}/>
    )
}



function CellMatrix(props) {
    const [matrix, setMatrix] = useState(Array(9)(9).fill('#000000'))
    
    const HandleClick = (i) => {
        return (
            CellMatrix.setMatrix[i]
        )
    }
    
    const RenderCell = (i) => {
        return (
            <Cell
                i={i}
                onClick={HandleClick(i)}
            />
        )
    }

    const jsxMatrix = matrix.map((row) =>
    <div key={row.toString()}>
        {row.map((v) =>
            <Cell key={v.toString()}/>
        )}
    </div>
)


    return (
        <div>
        <Cell x='23' y='1'/>
        <Cell x='2' y='1'/>
        </div>
    )
}

const app = document.getElementById('app');
ReactDOM.render(<CellMatrix/>, app);


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