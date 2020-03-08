import React, { useState, useRef, useEffect } from 'react'
import ReactDOM from 'react-dom'
import {useInterval} from './components/custom-useinterval'
import {ColorSelector} from './components/color-selector'
import {CellCodeTextarea} from './components/cell-code-textarea'
import {CellControlButton} from './components/cell-control-button'



function GetFetchData(url, init={}) {
    async function fetchData() {
        const response = await fetch(url,init)
        const json = await response.json()
        //const json = await response
        console.log(json)
    }
    return fetchData()
}

function GetHexColor(octR = 0, octG = 0, octB = 0) {
    const hexR = Number(octR).toString(16)
    const hexG = Number(octG).toString(16)
    const hexB = Number(octB).toString(16)

    const paddedHexR = ('00' + hexR).slice(-2)
    const paddedHexG = ('00' + hexG).slice(-2)
    const paddedHexB = ('00' + hexB).slice(-2)

    return paddedHexR + paddedHexG + paddedHexB
}



function Cell(props) {
    const btnstyle = {
        height : "40px",
        width  : "40px",
        background : props.color,
    }
    return (
        <button onClick={()=>props.onClick()} style={btnstyle} />
    )
}



function CellMatrix(props) {
    const CELL_ROW_NUM      = 10
    const CELL_COL_NUM      = 10
    const CELL_ROW_I        = CELL_ROW_NUM - 1
    //const CELL_COL_INDEX    = CELL_COL_NUM - 1
    const CELL_MAX_NUM      = CELL_ROW_NUM * CELL_COL_NUM
    const CELL_MAX_INDEX    = CELL_MAX_NUM - 1

    const [cellColor, setCellColor] = useState(Array(CELL_MAX_NUM).fill('#ffffff'))
    const [r, setR] = useState(0)
    const [g, setG] = useState(0)
    const [b, setB] = useState(0)
    const [cellCode, setCellCode] = useState("")
    const [contorolState, setControlState] = useState("stop")

    const cellColorData = new FormData()

    cellColorData.append("num",1)
    cellColorData.append("cellColorData",JSON.stringify(cellColor))
    // cellColor.forEach((color, i) => {
    //     cellColorData.append(i,color)
    // })

    useEffect(
        () =>{
            GetFetchData(
                '../local/save',
                {
                    method: "POST",
                    //Headersを書き忘れるとエラーになる
                    headers: {"X-CSRF-TOKEN": csrf_token},
                }
            )
        },
        []
    )
    
    useInterval(
        () => {
            GetFetchData(
                    '../local/stop',
                    {
                        method: "POST",
                        //Headersを書き忘れるとエラーになる
                        headers: {"X-CSRF-TOKEN": csrf_token},
                        body:cellColorData
                    }
            )
        },
        1000
    );

    function HandleClick(i, color) {
        const newCellColor = cellColor.slice();
        newCellColor[i] = color
        setCellColor(newCellColor)
        console.log(i)
        console.log(color)
    }

    function RenderCells() {
        const tempCells = Array(CELL_ROW_NUM).fill([])
        for(let i = 0; i <= CELL_ROW_I; i++) {
            tempCells[i] = Array(CELL_COL_NUM).fill(0)
        }

        const cells = tempCells.map((rows, rowI) =>
            <div key = {rowI.toString()}>
                {rows.map((col, colI) =>
                    <Cell
                        key = {colI.toString()}
                        onClick = {
                            ()=>HandleClick(
                                 CELL_COL_NUM * rowI + colI
                                ,'#' + GetHexColor(r,g,b)
                            )
                        }
                        color = {cellColor[CELL_COL_NUM * rowI + colI]}
                    />
                )}
            </div>
        )
        console.log(cells)

        return (
            <div>{cells}</div>
        )
    }


    return (
        <div>
            <form method="POST" action="">
                <input type="hidden" name="_token" value={csrf_token}/>
                <CellCodeTextarea value={cellCode} onChange={setCellCode}/>
                <CellControlButton value={"run"} onChange={setControlState} content={"実行"}/>
                <CellControlButton value={"stop"} onChange={setControlState} content={"停止"}/>
                <CellControlButton value={"save"} onChange={setControlState} content={"保存"} type={"submit"}/>
            </form>
            {RenderCells()}
            <p>
                R:<ColorSelector value={r} onChange={setR}/>
                G:<ColorSelector value={g} onChange={setG}/>
                B:<ColorSelector value={b} onChange={setB}/>
            </p>
        </div>
    )
}



const app = document.getElementById('app')
ReactDOM.render(<CellMatrix/>, app)







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