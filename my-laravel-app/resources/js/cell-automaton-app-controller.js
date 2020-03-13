import React, { useState, useRef, useEffect } from 'react'
import ReactDOM from 'react-dom'
import {useInterval} from './components/custom-useinterval'

// import {ColorSelector} from './components/color-selector'
// import {CellCodeTextarea} from './components/cell-code-textarea'
import {CellControlButton} from './components/cell-control-button'
import {CellMatrix, cell} from './components/cell-matrix'


import AceEditor from "react-ace";
import "ace-builds/src-noconflict/mode-python";
import "ace-builds/src-noconflict/theme-github";




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
    const [cellCode, setCellCode]   = useState('')
    const [colorR, setColorR]       = useState(0)
    const [colorG, setColorG]       = useState(0)
    const [colorB, setColorB]       = useState(0)

    const cellCalcStateIsRun    = 'Run'
    const cellCalcStateIsStop   = 'Stop'
    const [cellCalcState, setCellCalcState] = useState(cellCalcStateIsStop)

    const codeChangeNotRequested    = 'NotRequested'
    const codeChangeRequested       = 'Requested'
    const [codeChangeState, setCodeChangeState] = useState(codeChangeNotRequested)

    const [codeSaveButtonCounter, setCodeSaveButtonCounter]             = useState(0)
    const [cellColorSaveButtonCounter, setCellColorSaveButtonCounter]   = useState(0)
    
    const isFirstCodeSaveSend = useRef(true)
    const isFirstCellColorSaveSend = useRef(true)

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
                    console.log(result.cell_color)
                    setCellColor(result.cell_color)
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
            if(isFirstCodeSaveSend.current) {
                isFirstCodeSaveSend.current = false
            }
            else {
                const sendData = new FormData()
                sendData.append('id',G_LOCAL_CELL_ID)
                sendData.append('cell_code', cellCode)
                const response = GetFetchData(
                    '../local/codesave',
                    {
                        method: 'POST',
                        headers: {'X-CSRF-TOKEN': G_CSRF_TOKEN},
                        body: sendData
                    }
                )
                setCodeChangeState(codeChangeRequested)
            }
        },
        [codeSaveButtonCounter]
    )
    // 初期セル色保存送信
    useEffect(
        () =>{
            if(isFirstCellColorSaveSend.current) {
                isFirstCellColorSaveSend.current = false
            }
            else {
                const sendData = new FormData()
                sendData.append('id',G_LOCAL_CELL_ID)
                sendData.append('cell_color', cellColor)
                const response = GetFetchData(
                    '../local/cellcolorsave',
                    {
                        method: 'POST',
                        headers: {'X-CSRF-TOKEN': G_CSRF_TOKEN},
                        body: sendData
                    }
                )
            }
        },
        [cellColorSaveButtonCounter]
    )
    // 実行中の送信
    useInterval(
        () => {
            if(cellCalcState === cellCalcStateIsRun){
                if(codeChangeState === codeChangeRequested) {
                    const sendData = new FormData()
                    sendData.append('id',G_LOCAL_CELL_ID)
                    sendData.append('cell_code',cellCode)
                    sendData.append('cell_color',JSON.stringify(cellColor))
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
                    sendData.append('cell_color',JSON.stringify(cellColor))
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

    return (
        <div>
            <CellMatrix
                MAX_CELL_ROW_NUM={MAX_CELL_ROW_NUM}
                MAX_CELL_COL_NUM={MAX_CELL_COL_NUM}
                setCellColor={setCellColor}
                cellColor={cellColor}
                colorR={colorR}
                colorG={colorG}
                colorB={colorB}
            />
            {/* <CellCodeTextarea value={cellCode} onChange={setCellCode}/> */}
            <CellControlButton value={cellCalcStateIsRun} onChange={setCellCalcState} content={"実行"}/>
            <CellControlButton value={cellCalcStateIsStop} onChange={setCellCalcState} content={"停止"}/>
            <CellControlButton value={codeSaveButtonCounter} onChange={()=>{setCodeSaveButtonCounter(codeSaveButtonCounter + 1)}} content={"コード保存"}/>
            <CellControlButton value={codeSaveButtonCounter} onChange={()=>{setCellColorSaveButtonCounter(cellColorSaveButtonCounter + 1)}} content={"初期セル色保存"}/>

            <p>R:<input type="text" value={colorR} onChange={(event)=>setColorR(event.target.value)}/>:<Slider value={colorR} onChange={setColorR} min="0" max="255"/></p>
            <p>G:<input type="text" value={colorG} onChange={(event)=>setColorG(event.target.value)}/>:<Slider value={colorG} onChange={setColorG} min="0" max="255"/></p>
            <p>B:<input type="text" value={colorB} onChange={(event)=>setColorB(event.target.value)}/>:<Slider value={colorB} onChange={setColorB} min="0" max="255"/></p>

            <div>
                <AceEditor
                    mode="python"
                    theme="github"
                    name="aceCodeEditor"
                    value={cellCode}
                    onChange={setCellCode}
                />
            </div>
        </div>
    )
}




function Slider(props) {
    function handleChange(event) {
        props.onChange(event.target.value)
        //console.log(event.target.value)
    }
    return (
        <input type="range" value={props.value} min={props.min} max={props.max} onChange={handleChange}/>
    )
}







const localApp = document.getElementById('local-app')
ReactDOM.render(<CellAutomatonAppController/>, localApp)




/* R:<ColorSelector value={colorR} onChange={setColorR}/> */