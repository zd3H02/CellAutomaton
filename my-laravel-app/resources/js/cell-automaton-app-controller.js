import React, { useState, useRef, useEffect } from 'react'
import ReactDOM from 'react-dom'
import {useInterval} from './components/custom-useinterval'
import {ColorSelector} from './components/color-selector'
import {CellCodeTextarea} from './components/cell-code-textarea'
import {CellControlButton} from './components/cell-control-button'
import {CellMatrix, cell} from './components/cell-matrix'

function GetFetchData(url, init={}) {
    async function fetchData() {
        const response = await fetch(url,init)
        const json = await response.json()
        //const json = await response
        //console.log(json)
        return json
    }
    return fetchData()
}


function CellAutomatonAppController(props) {
    const [MAX_CELL_ROW_NUM,    SET_ONLY_FORST_USE_MAX_CELL_ROW_NUM]    = useState(0)
    const [MAX_CELL_COL_NUM,    SET_ONLY_FORST_USE_MAX_CELL_COL_NUM]    = useState(0)
    const [MAX_CELL_NUM,        SET_ONLY_FORST_USE_MAX_CELL_NUM]        = useState(0)

    const [cellColor, setCellColor] = useState(Array(MAX_CELL_NUM).fill('#ffffff'))
    const [cellCode, setCellCode] = useState('')
    const [colorR, setColorR] = useState(0)
    const [colorG, setColorG] = useState(0)
    const [colorB, setColorB] = useState(0)

    const cellCalcStateIsRun    = 'Run'
    const cellCalcStateIsStop   = 'Stop'
    const [cellCalcState, setCellCalcState] = useState(cellCalcStateIsStop)

    const codeChangeNotRequested    = 'NotRequested'
    const codeChangeRequested       = 'Requested'
    const [codeChangeState, setCodeChangeState] = useState(codeChangeNotRequested)

    const [saveButtonCounter, setSaveButtonCounter] = useState(0)

    // Laravelでデータ送信するときに下記を書き忘れるとエラーになるので注意する。
    // headers: {'X-CSRF-TOKEN': G_CSRF_TOKEN}
    // 初回送信
    useEffect(
        () =>{
            const sendData = new FormData()
            sendData.append('id',G_LOCAL_CELL_ID)
            const response = GetFetchData(
                '../local/first',
                {
                    method: 'POST',
                    headers: {'X-CSRF-TOKEN': G_CSRF_TOKEN},
                    body: sendData
                }
            )
            response.then(
                result=>{
                    // console.log(result.cell_color_data)
                    setCellColor(result.cell_color_data)
                    SET_ONLY_FORST_USE_MAX_CELL_ROW_NUM(result.MAX_CELL_ROW_NUM)
                    SET_ONLY_FORST_USE_MAX_CELL_COL_NUM(result.MAX_CELL_COL_NUM)
                    SET_ONLY_FORST_USE_MAX_CELL_NUM(result.MAX_CELL_NUM)
                }
            )
        },
        []
    )
    // コード保存送信
    useEffect(
        () =>{
            const sendData = new FormData()
            sendData.append('id',G_LOCAL_CELL_ID)
            sendData.append('cell_code', cellCode)
            const response = GetFetchData(
                '../local/save',
                {
                    method: 'POST',
                    headers: {'X-CSRF-TOKEN': G_CSRF_TOKEN},
                    body: sendData
                }
            )
            setCodeChangeState(codeChangeRequested)
        },
        [saveButtonCounter]
    )
    // 実行中の送信
    useInterval(
        () => {
            if(cellCalcState === cellCalcStateIsRun){
                if(codeChangeState === codeChangeRequested) {
                    const sendData = new FormData()
                    sendData.append('id',G_LOCAL_CELL_ID)
                    sendData.append('cell_code',cellCode)
                    sendData.append('cell_color_data',JSON.stringify(cellColor))
                    const response = GetFetchData(
                        '../local/change',
                        {
                            method: 'POST',
                            headers: {'X-CSRF-TOKEN': G_CSRF_TOKEN},
                            body:sendData
                        }
                    )
                    setCodeChangeState(codeChangeNotRequested)
                }
                else {
                    const sendData = new FormData()
                    sendData.append('cell_color_data',JSON.stringify(cellColor))
                    const response = GetFetchData(
                        '../local/calc',
                        {
                            method: 'POST',
                            headers: {'X-CSRF-TOKEN': G_CSRF_TOKEN},
                            body:sendData
                        }
                    )
                    response.then(
                        result=>{
                            setCellColor(result)
                            // console.log(cellColor)
                        }
                    )
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

    return (
        <div>
            <CellMatrix 
                MAX_CELL_ROW_NUM={MAX_CELL_ROW_NUM}
                MAX_CELL_COL_NUM={MAX_CELL_COL_NUM}
                HandleClick={HandleClick}
                cellColor={cellColor}
                colorR={colorR}
                colorG={colorG}
                colorB={colorB}
            />
            <CellCodeTextarea value={cellCode} onChange={setCellCode}/>
            <CellControlButton value={cellCalcStateIsRun} onChange={setCellCalcState} content={'実行'}/>
            <CellControlButton value={cellCalcStateIsStop} onChange={setCellCalcState} content={'停止'}/>
            <CellControlButton value={saveButtonCounter} onChange={()=>{setSaveButtonCounter(saveButtonCounter + 1)}} content={'保存'}/>
            <p>
                R:<ColorSelector value={colorR} onChange={setColorR}/>
                G:<ColorSelector value={colorG} onChange={setColorG}/>
                B:<ColorSelector value={colorB} onChange={setColorB}/>
            </p>
        </div>
    )
}







const localApp = document.getElementById('local-app')
ReactDOM.render(<CellAutomatonAppController/>, localApp)




