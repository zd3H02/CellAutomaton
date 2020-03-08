import React, { useState, useRef, useEffect } from 'react'
import ReactDOM from 'react-dom'
import {useInterval} from './components/custom-useinterval'
import {ColorSelector} from './components/color-selector'
import {CellCodeTextarea} from './components/cell-code-textarea'
import {CellControlButton} from './components/cell-control-button'

function GetFetchData(url, init={}) {
    async function fetchData() {
        const response = await fetch(url,init)
        //const json = await response.json()
        const json = await response
        console.log(json)
        return json
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
        height : '40px',
        width  : '40px',
        background : props.color,
    }
    return (
        <button onClick={()=>props.onClick()} style={btnstyle} />
    )
}


function CellMatrix(props) {
    const CELL_ROW_NUM      = 10
    const CELL_COL_NUM      = 10
    const CELL_MAX_NUM      = CELL_ROW_NUM * CELL_COL_NUM

    const [cellColor, setCellColor] = useState(Array(CELL_MAX_NUM).fill('#ffffff'))
    const [r, setR] = useState(0)
    const [g, setG] = useState(0)
    const [b, setB] = useState(0)
    const [cellCode, setCellCode] = useState('')

    const cellCalcStateIsRun    = 'Run'
    const cellCalcStateIsStop   = 'Stop'
    const [cellCalcState, setCellCalcState] = useState(cellCalcStateIsStop)

    const codeSaveStateIsNonRequested       = 'NonRequested'
    const codeSaveStateIsRequested          = 'Requested'
    const codeSaveStateIsRequestCompleted   = 'RequestCompleted'
    const [codeSaveState, setCodeSaveState] = useState(codeSaveStateIsNonRequested)

    // Laravelでデータ送信するときに下記を書き忘れるとエラーになるので注意する。
    // headers: {'X-CSRF-TOKEN': G_CSRF_TOKEN}
    useEffect(
        () =>{
            const sendData = new FormData()
            sendData.append('id',G_LOCAL_CELL_ID)
            GetFetchData(
                '../local/first',
                {
                    method: 'POST',
                    headers: {'X-CSRF-TOKEN': G_CSRF_TOKEN},
                    body: sendData
                }
            )
        },
        []
    )
    useInterval(
        () => {
            if(cellCalcState === cellCalcStateIsRun){
                if(codeSaveState === codeSaveStateIsRequestCompleted) {
                    const sendData = new FormData()
                    sendData.append('codeChangeReq',true)
                    sendData.append('cell_name','')
                    sendData.append('cell_code',cellCode)
                    sendData.append('cell_color_data',JSON.stringify(cellColor))
                    GetFetchData(
                        '../local/save',
                        {
                            method: 'POST',
                            headers: {'X-CSRF-TOKEN': G_CSRF_TOKEN},
                            body:sendData
                        }
                    )
                }
                else {
                    const sendData = new FormData()
                    sendData.append('codeChangeReq',false)
                    sendData.append('cell_color_data',JSON.stringify(cellColor))
                    const cellColorResult = GetFetchData(
                        '../local/calc',
                        {
                            method: 'POST',
                            headers: {'X-CSRF-TOKEN': G_CSRF_TOKEN},
                            body:sendData
                        }
                    )
                    cellColorResult.then(
                        result=>{
                            setCellColor(result)
                        }
                    )
                    // console.log(cellColorResult)
                }

            }
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
        for(let i = 0; i < CELL_ROW_NUM; i++) {
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
            <CellCodeTextarea value={cellCode} onChange={setCellCode}/>
            <CellControlButton value={cellCalcStateIsRun} onChange={setCellCalcState} content={'実行'}/>
            <CellControlButton value={cellCalcStateIsStop} onChange={setCellCalcState} content={'停止'}/>
            <CellControlButton value={codeSaveStateIsRequested} onChange={setCodeSaveState} content={'保存'}/>
            {RenderCells()}
            <p>
                R:<ColorSelector value={r} onChange={setR}/>
                G:<ColorSelector value={g} onChange={setG}/>
                B:<ColorSelector value={b} onChange={setB}/>
            </p>
        </div>
    )
}



const localApp = document.getElementById('local-app')
ReactDOM.render(<CellMatrix/>, localApp)







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