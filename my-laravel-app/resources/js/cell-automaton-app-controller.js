import React, { useState, useRef, useEffect } from 'react'
import ReactDOM from 'react-dom'
import {useInterval} from './components/custom-useinterval'

import {CellMatrix, cell} from './components/cell-matrix'
import {ColorValidation} from './components/color-validation'


import AceEditor from "react-ace"
import "ace-builds/src-noconflict/mode-python"
import "ace-builds/src-noconflict/theme-github"

import {GetHexColor, GetFetchData} from './components/utility'


import { useHotkeys } from 'react-hotkeys-hook'

import {
    Container,
    Row,
    Col,
    Button,
} from 'react-bootstrap'



function CellAutomatonAppController(props) {
    const [MAX_CELL_ROW_NUM,    SET_ONLY_FORST_USE_MAX_CELL_ROW_NUM]    = useState(0)
    const [MAX_CELL_COL_NUM,    SET_ONLY_FORST_USE_MAX_CELL_COL_NUM]    = useState(0)
    const [MAX_CELL_NUM,        SET_ONLY_FORST_USE_MAX_CELL_NUM]        = useState(0)

    const [cellColors, setCellColors] = useState(Array(MAX_CELL_NUM).fill('#ffffff'))
    const [cellCode, setCellCode]   = useState('')

    const [acceptedColorCodes, setAcceptedColorCodes] = useState(Array('#000000','#ffffff','#ff0000','#00ff00','#0000ff'))
    const [inUseColor, setInUseColor] = useState(0)
    function getInUseColor() {
        return acceptedColorCodes[inUseColor]
    }

    const cellCalcStateIsRun    = 'Run'
    const cellCalcStateIsStop   = 'Stop'
    const [cellCalcState, setCellCalcState] = useState(cellCalcStateIsStop)

    const [codeExecCmdOutput, setCodeExecCmdOutput] = useState('')
    const [codeExecCmdStatus, setCodeExecCmdStatus] = useState('')

    const [cellName, setCellName] = useState('')
    const [cellMemo, setCellMemo] = useState('')

    //ショートカットキー
    const [shrotCutRun, setShrotCutRun] = useState(0)
    const shrotCutKeyRun = 'shift+ctrl+alt+a'
    useHotkeys(shrotCutKeyRun, () => setShrotCutRun(setCellCalcState(cellCalcStateIsRun)))

    const [shrotCutStop, setShrotCutStop] = useState(0)
    const shrotCutKeyStop = 'shift+ctrl+alt+s'
    useHotkeys(shrotCutKeyStop, () => setShrotCutStop(setCellCalcState(cellCalcStateIsStop)))

    const isFirstCodeSaveSend = useRef(true)
    const codeSaveStateIsSaving = 'Saving'
    const codeSaveStateIsSaved  = 'Saved'
    const [codeSaveState, setcodeSaveState] = useState(codeSaveStateIsSaved)
    const [shrotCutCodeSave, setShrotCutCodeSave] = useState(0)
    const shrotCutKeyCodeSave = 'shift+ctrl+alt+d'
    useHotkeys(shrotCutKeyCodeSave, () => setShrotCutCodeSave(prevCount => prevCount + 1))

    const isFirstCellColorsSaveSend = useRef(true)
    const cellColorsSaveStateIsSaving = 'Saving'
    const cellColorsSaveStateIsSaved  = 'Saved'
    const [cellColorsSaveState, setCellColorsSaveState] = useState(cellColorsSaveStateIsSaved)
    const [shrotCutColorsSave, setShrotCutColorsSave] = useState(0)
    const shrotCutKeyColorsSave = 'shift+ctrl+alt+z'
    useHotkeys(shrotCutKeyColorsSave, () => setShrotCutColorsSave(prevCount => prevCount + 1))

    const isFirstAllSaveSend = useRef(true)
    const allSaveStateIsSaving = 'Saving'
    const allSaveStateIsSaved  = 'Saved'
    const [allSaveState, setAllSaveState] = useState(allSaveStateIsSaved)
    const [shrotCutAllSave, setShrotCutAllSave] = useState(0)
    const shrotCutKeyAllSave = 'shift+ctrl+alt+x'
    useHotkeys(shrotCutKeyAllSave, () => setShrotCutAllSave(prevCount => prevCount + 1))


    const [isDisplayMatrix, setIsDisplayMatrix] = useState(false)
    const isFirstDisplayMatrix = useRef(true)
    const [shrotCutDisplayMatrix, setShrotCutDisplayMatrix] = useState(0)
    const shrotCutKeyDisplayMatrix = 'shift+ctrl+alt+q'
    useHotkeys(shrotCutKeyDisplayMatrix, () => setShrotCutDisplayMatrix(prevCount => prevCount + 1))

    const isFirstShrotCutFill = useRef(true)
    const [shrotCutFill, setShrotCutFill] = useState(0)
    const shrotCutKeyFill = 'shift+ctrl+alt+w'
    useHotkeys(shrotCutKeyFill, () => setShrotCutFill(prevCount => prevCount + 1))

    const [calcWaitingForReception, setCalcWaitingForReception] = useState(false)

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
                    // console.log(result.cell_code)
                    setCellColors(result.cell_colors)
                    setCellName(result.cell_name)
                    setCellCode(result.cell_code)
                    setCellMemo(result.cell_memo)
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
            else if(codeSaveState === codeSaveStateIsSaved) {
                setcodeSaveState(codeSaveStateIsSaving)
                const sendData = new FormData()
                sendData.append('id', G_LOCAL_CELL_ID)
                sendData.append('cell_name', cellName)
                sendData.append('cell_code', cellCode)
                sendData.append('cell_memo', cellMemo)
                const response = GetFetchData(
                    '../local/codesave',
                    {
                        method: 'POST',
                        headers: {'X-CSRF-TOKEN': G_CSRF_TOKEN},
                        body: sendData
                    }
                )
                response.then(
                    result=>{
                        setcodeSaveState(codeSaveStateIsSaved)
                    }
                )
            }
        },
        [shrotCutCodeSave]
    )
    // 初期セル色保存送信
    useEffect(
        () =>{
            if(isFirstCellColorsSaveSend.current) {
                isFirstCellColorsSaveSend.current = false
            }
            else if(cellColorsSaveState === cellColorsSaveStateIsSaved) {
                setCellColorsSaveState(cellColorsSaveStateIsSaving)
                const sendData = new FormData()
                sendData.append('id',G_LOCAL_CELL_ID)
                sendData.append('cell_colors', cellColors)
                const response = GetFetchData(
                    '../local/cellcolorsave',
                    {
                        method: 'POST',
                        headers: {'X-CSRF-TOKEN': G_CSRF_TOKEN},
                        body: sendData
                    }
                )
                response.then(
                    result=>{
                        setCellColorsSaveState(cellColorsSaveStateIsSaved)
                    }
                )
            }
        },
        [shrotCutColorsSave]
    )
    // 全て保存送信
    useEffect(
        () =>{
            if(isFirstAllSaveSend.current) {
                isFirstAllSaveSend.current = false
            }
            else if(allSaveState === allSaveStateIsSaved) {
                setAllSaveState(allSaveStateIsSaving)
                const sendData = new FormData()
                sendData.append('id',G_LOCAL_CELL_ID)
                sendData.append('cell_name', cellName)
                sendData.append('cell_code', cellCode)
                sendData.append('cell_memo', cellMemo)
                sendData.append('cell_colors', cellColors)
                const response = GetFetchData(
                    '../local/allsave',
                    {
                        method: 'POST',
                        headers: {'X-CSRF-TOKEN': G_CSRF_TOKEN},
                        body: sendData
                    }
                )
                response.then(
                    result=>{
                        setAllSaveState(allSaveStateIsSaved)
                    }
                )
            }
        },
        [shrotCutAllSave]
    )
    // 実行中の送信
    useInterval(
        cellCalcState === cellCalcStateIsRun ?
            () => {
                setCalcWaitingForReception(true)
                const sendData = new FormData()
                sendData.append('id',G_LOCAL_CELL_ID)
                sendData.append('cell_colors',JSON.stringify(cellColors))
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
                        setCalcWaitingForReception(false)
                        setCellColors(result.cell_colors)
                        setCodeExecCmdOutput(result.code_exec_cmd_output)
                        setCodeExecCmdStatus(result.code_exec_cmd_status)
                        // console.log(cellColors)
                    }
                )
            }
        :
            ()=>{setCalcWaitingForReception(false)},
        3000
    )

    useEffect(
        () => {
            if(isFirstDisplayMatrix.current) {
                isFirstDisplayMatrix.current = false
            }
            else {
                setIsDisplayMatrix(!isDisplayMatrix)
            }
        },
        [shrotCutDisplayMatrix]
    )

    useEffect(
        () => {
            if(isFirstShrotCutFill.current) {
                isFirstShrotCutFill.current = false
            }
            else {
                setCellColors(Array(MAX_CELL_NUM).fill(getInUseColor()))
            }
        },
        [shrotCutFill]
    )




    const inUseColorStyle = {
        height: "40px",
        width: "40px",
        background: getInUseColor(),
    }
    function getAcceptedColorCodesStyle(i) {
        return {
            height: "40px",
            width: "40px",
            background: acceptedColorCodes[i],
        }
    }
    const cellMatrixColStyle = {
        minWidth: '850px',
    }
    const localCellStyle = {
        maxWidth: '1920px',
    }
    const btnLikeStyle ={
        margin: '4px 4px 0px 4px',
        lineHeight: '1.5',
        display: 'inline-block',
        fontWeight: '400',
        textAlign: 'center',
        verticalAlign: 'middle',
        border: '1px solid transparent',
        padding: '.375rem .75rem',
        fontSize: '1rem',
        borderRadius: '.25rem'
    }
    return (
        <Container fluid style={localCellStyle}>
            <Row>
                <Col md={6} style={cellMatrixColStyle}>
                    {/* <div className="float-right"> */}
                    <p className="align-middle">
                        ライフゲーム名：<input className="align-middle" type="text" value={cellName} onChange={event=>setCellName(event.target.value)}/>
                    </p>
                    <CellMatrix
                        MAX_CELL_ROW_NUM={MAX_CELL_ROW_NUM}
                        MAX_CELL_COL_NUM={MAX_CELL_COL_NUM}
                        cellColors={cellColors}
                        setCellColors={setCellColors}
                        acceptedColorCode={getInUseColor()}
                        className="border border-secondary rounded d-inline-block"
                        isDisplayMatrix={isDisplayMatrix}
                    />
                    {/* </div> */}
                </Col>
                <Col md={6}>
                    <Row>
                        <Col md={6}>
                            <p className="mt-1 mb-0 p-0">
                                <Button
                                    className="border border-secondary rounded"
                                    onClick={() => setIsDisplayMatrix(!isDisplayMatrix)}
                                >
                                {isDisplayMatrix ? "行列番号非表示" : "行列番号表示"}({shrotCutKeyDisplayMatrix})
                                </Button>
                            </p>
                            <p className="mt-1 mb-0 p-0">
                                <Button
                                    className="border border-secondary rounded"
                                    onClick={() => setCellColors(Array(MAX_CELL_NUM).fill(getInUseColor()))}
                                >
                                塗りつぶし({shrotCutKeyFill})
                                </Button>
                            </p>
                            <div className="mt-1 border border-secondary rounded" style={inUseColorStyle}></div>
                            {acceptedColorCodes.map((acceptedColorCode, i) => {
                                return(
                                    <Button
                                        key = {i}
                                        className="mt-1 border border-secondary rounded"
                                        style={getAcceptedColorCodesStyle(i)}
                                        onClick={()=>setInUseColor(i)}
                                    >
                                    </Button>
                                )
                            })}
                            {acceptedColorCodes.map((acceptedColorCode, i) => {
                                return(
                                    <ColorValidation
                                        key = {i}
                                        i = {i}
                                        className={"mt-1" + " " + (inUseColor === i ? "" : "d-none")}
                                        acceptedColorCode={acceptedColorCode}
                                        setAcceptedColorCode={(colorCode)=>{
                                            const newColorCodes = acceptedColorCodes.slice()
                                            newColorCodes[i] = colorCode
                                            setAcceptedColorCodes(newColorCodes)
                                        }}
                                    />
                                )
                            })}
                        </Col>
                        <Col md={6}>
                            <label htmlFor="cellMemo">メモ:</label>
                            <textarea
                                className="form-control"
                                id="cellMemo"
                                rows="8"
                                cols="40"
                                value={cellMemo}
                                onChange={(event)=>setCellMemo(event.target.value)}
                            >
                            </textarea>
                        </Col>
                    </Row>
                    <p className="m-0 p-0">
                    {calcWaitingForReception ?
                            <span className="rounded bg-danger text-white align-middle" style={btnLikeStyle}>受信待ち</span>
                        :   <span className="rounded bg-primary  text-white align-middle" style={btnLikeStyle}>送信準備</span>
                    }
                    <Button
                        className={"mt-1 mx-1" + " " + (cellCalcState === cellCalcStateIsRun ? "bg-primary border-primary" : "bg-secondary border-secondary")}
                        // value={cellCalcStateIsRun}
                        // onClick={()=>setCellCalcState(event.target.value)}
                        onClick={()=>setCellCalcState(cellCalcStateIsRun)}
                    >
                        実行<small>({shrotCutKeyRun})</small>
                    </Button>
                    <Button
                        className={"mt-1 mx-1" + " " + (cellCalcState === cellCalcStateIsStop ? "bg-primary border-primary" : "bg-secondary border-secondary")}
                        // value={cellCalcStateIsStop}
                        // onClick={event=>setCellCalcState(event.target.value)}
                        onClick={()=>setCellCalcState(cellCalcStateIsStop)}
                    >
                        停止<small>({shrotCutKeyStop})</small>
                    </Button>
                    <Button
                        className={"mt-1 mx-1" + " " + (codeSaveState === codeSaveStateIsSaved ? "bg-success border-success" : "bg-secondary border-secondary")}
                        onClick={()=>setShrotCutCodeSave(prevCount => prevCount + 1)}
                    >
                        コード保存<small>({shrotCutKeyCodeSave})</small>
                    </Button>
                    <Button
                        className={"mt-1 mx-1" + " " + (cellColorsSaveState === cellColorsSaveStateIsSaved ? "bg-success border-success" : "bg-secondary border-secondary")}
                        onClick={()=>setShrotCutColorsSave(prevCount => prevCount + 1)}
                    >
                        初期セル色保存<small>({shrotCutKeyColorsSave})</small>
                    </Button>
                    <Button
                        className={"mt-1 mx-1" + " " + (allSaveState === allSaveStateIsSaved ? "bg-success border-success" : "bg-secondary border-secondary")}
                        onClick={()=>setShrotCutAllSave(prevCount => prevCount + 1)}
                    >
                        全て保存<small>({shrotCutKeyAllSave})</small>
                    </Button>
                    </p>
                    <div>
                        <AceEditor
                            height="400px"
                            width="920px"
                            mode="python"
                            theme="github"
                            name="aceCodeEditor"
                            value={cellCode === null ? '' : cellCode}
                            onChange={setCellCode}
                            className="border border-secondary rounded p-1  my-1"
                        />
                    </div>
                    <p className="m-0">出力：</p>
                    <div className={"border border-secondary rounded p-1 my-1 overflow-auto" + " " + (codeExecCmdOutput === "" ? "d-none" : "")}>
                        {codeExecCmdOutput}
                    </div>
                    <p className="m-0">ステータス：</p>
                    <div className={"border border-secondary rounded p-1 my-1 overflow-auto" + " " + (codeExecCmdOutput === "" ? "d-none" : "")}>
                        {codeExecCmdStatus}
                    </div>
                </Col>
            </Row>
        </Container>
    )
}

const localApp = document.getElementById('local-app')
ReactDOM.render(<CellAutomatonAppController/>, localApp)



                        // <Button className="border border-secondary rounded" style={acceptedColorCode00Style} onClick={()=>setInUseColor(0)}></Button>
                        // <Button className="border border-secondary rounded" style={acceptedColorCode01Style} onClick={()=>setInUseColor(1)}></Button>
/* R:<ColorSelector value={colorR} onChange={setColorR}/> */

/* <CellCodeTextarea value={cellCode} onChange={setCellCode}/> */
/* <CellControlButton value={codeSaveButtonCounter}  content={"コード保存"}/> */
/* <CellControlButton value={cellCalcStateIsRun} onChange={setCellCalcState} content={"実行"}/> */
/* <CellControlButton value={cellCalcStateIsStop} onChange={setCellCalcState} content={"停止"}/> */
/* <CellControlButton value={codeSaveButtonCounter}  content={"初期セル色保存"}/> */

/* :<Slider value={colorR} onChange={setColorR} min="0" max="255"/> */
/* :<Slider value={colorG} onChange={setColorG} min="0" max="255"/></p> */
/* :<Slider value={colorB} onChange={setColorB} min="0" max="255"/> */



// function Slider(props) {
//     function handleChange(event) {
//         props.onChange(event.target.value)
//         //console.log(event.target.value)
//     }
//     return (
//         <input type="range" value={props.value} min={props.min} max={props.max} onChange={handleChange}/>
//     )
// }


// import {ColorSelector} from './components/color-selector'
// import {CellCodeTextarea} from './components/cell-code-textarea'
// import {CellControlButton} from './components/cell-control-button'



    // useEffect(
    //     () => {
    //         const regex = /^#[\da-fA-F]{6}$/;
    //         const isValidationOk = inputColorCode.match(regex)
    //         const isCheckExecute = colorValidationState === colorValidationStateIsInputted
    //         if(isCheckExecute) {
    //             if(isValidationOk) {
    //                 setColorValidationState(colorValidationStateIsAccepted)
    //                 setAcceptedColorCode(inputColorCode)
    //                 setInputColorR(parseInt(inputColorCode.slice(1,3),16))
    //                 setInputColorG(parseInt(inputColorCode.slice(3,5),16))
    //                 setInputColorB(parseInt(inputColorCode.slice(5,7),16))
    //             }
    //             else {
    //                 setColorValidationState(colorValidationStateIsError)
    //             }
    //         }
    //     },
    //     [inputColorCode]
    // )

    // const [acceptedcolorCode, setAcceptedColorCode] = useState('000000')
    // const [inputColorCode, setInputColorCode]     = useState('000000')
    // const colorValidationStateIsAccepted = 'Accepted'
    // const colorValidationStateIsError    = 'Error'
    // const [colorValidationState, setColorValidationState] = useState(colorValidationStateIsAccepted)

















                    // if(codeChangeState === codeChangeRequested) {
                //     const sendData = new FormData()
                //     sendData.append('id',G_LOCAL_CELL_ID)
                //     sendData.append('cell_code',cellCode)
                //     sendData.append('cell_colors',JSON.stringify(cellColors))
                //     const response = GetFetchData(
                //         '../local/change',
                //         {
                //             method: 'POST',
                //             headers: {'X-CSRF-TOKEN': G_CSRF_TOKEN},
                //             body:sendData
                //         }
                //     )
                //     setCodeChangeState(codeChangeNotRequested)
                // }
                // else {

                                // }
                                    // const codeChangeNotRequested    = 'NotRequested'
    // const codeChangeRequested       = 'Requested'
    // const [codeChangeState, setCodeChangeState] = useState(codeChangeNotRequested)




                    //     {/* <p>
                    //     Hex:<input type="text" value={inputColorCode} onChange={event=>setInputColorCode(event.target.value)}/>
                    //     :<input type="range" value={parseInt(inputColorCode.slice(1),16)} onChange={event=>setInputColorCode('#'+('000000'+Number(event.target.value).toString(16)).slice(-6))} min="0" max="16777215"/>
                    // </p>
                    // <p>
                    //     &nbsp;&nbsp;&nbsp;&nbsp;R:<input type="text" value={inputColorR} onChange={event=>setInputColorR(event.target.value)}/>
                    //     :<input type="range" value={inputColorR} onChange={event=>setInputColorR(event.target.value)} min="0" max="255"/>
                    // </p>
                    // <p>
                    //     &nbsp;&nbsp;&nbsp;&nbsp;G:<input type="text" value={inputColorG} onChange={event=>setInputColorG(event.target.value)}/>
                    //     :<input type="range" value={inputColorG} onChange={event=>setInputColorG(event.target.value)} min="0" max="255"/>
                    // </p>
                    // <p>
                    //     &nbsp;&nbsp;&nbsp;&nbsp;B:<input type="text" value={inputColorB} onChange={event=>setInputColorB(event.target.value)}/>
                    //     :<input type="range" value={inputColorB} onChange={event=>setInputColorB(event.target.value)} min="0" max="255"/>
                    // </p> */}








        // const colorValidationStateIsAccepted = 'Accepted'
        // const colorValidationStateIsError    = 'Error'
        // const [colorValidationState, setColorValidationState] = useState(colorValidationStateIsAccepted)
    // const [inputColorCode, setInputColorCode]       = useState('#000000')
    // const [inputColorR, setInputColorR] = useState(0)
    // const [inputColorG, setInputColorG] = useState(0)
    // const [inputColorB, setInputColorB] = useState(0)
    // const [inputColorCode2, setInputColorCode2]       = useState('#000000')
    // const [inputColorR2, setInputColorR2] = useState(0)
    // const [inputColorG2, setInputColorG2] = useState(0)
    // const [inputColorB2, setInputColorB2] = useState(0)
        // const [codeSaveButtonCounter, setCodeSaveButtonCounter]             = useState(0)
        // const [cellColorsSaveButtonCounter, setCellColorsSaveButtonCounter] = useState(0)

            // カラーコードバリデーション
    // const colorValidation = ColorValidation(
    //      acceptedColorCode
    //     ,setAcceptedColorCode
    // )

    // const colorValidation2 = ColorValidation(
    //     acceptedColorCode2
    //    ,setAcceptedColorCode2
    // )
    // カラーコードバリデーション
    // useEffect(
    //     () => {
    //         const isValidationOk = inputColorCode.match(/^#[\da-fA-F]{6}$/)
    //         if(isValidationOk) {
    //             setColorValidationState(colorValidationStateIsAccepted)
    //             setAcceptedColorCode(inputColorCode)
    //             setInputColorR(parseInt(inputColorCode.slice(1,3),16))
    //             setInputColorG(parseInt(inputColorCode.slice(3,5),16))
    //             setInputColorB(parseInt(inputColorCode.slice(5,7),16))

    //             setColorValidationState(colorValidationStateIsAccepted)
    //         }
    //         else {
    //             setColorValidationState(colorValidationStateIsError)
    //         }
    //     },
    //     [inputColorCode]
    // )
    // useEffect(
    //     () => {
    //         const isValidationOk = Number(inputColorR).toString(16).match(/^[\da-fA-F]{1,2}$/)
    //         if(isValidationOk) {
    //             const left  = acceptedColorCode.slice(0,1)
    //             const right = acceptedColorCode.slice(3)
    //             const paddedHex = ('00' + Number(inputColorR).toString(16)).slice(-2)
    //             const neewColorCode = left + paddedHex + right
    //             setAcceptedColorCode(neewColorCode)
    //             setInputColorCode(neewColorCode)

    //             setColorValidationState(colorValidationStateIsAccepted)
    //         }
    //         else {
    //             setColorValidationState(colorValidationStateIsError)
    //         }
    //     },
    //     [inputColorR]
    // )
    // useEffect(
    //     () => {
    //         const isValidationOk = Number(inputColorG).toString(16).match(/^[\da-fA-F]{1,2}$/)
    //         if(isValidationOk) {
    //             const left  = acceptedColorCode.slice(0,3)
    //             const right = acceptedColorCode.slice(5)
    //             const paddedHex = ('00' + Number(inputColorG).toString(16)).slice(-2)
    //             const neewColorCode = left + paddedHex + right
    //             setAcceptedColorCode(neewColorCode)
    //             setInputColorCode(neewColorCode)

    //             setColorValidationState(colorValidationStateIsAccepted)
    //         }
    //         else {
    //             setColorValidationState(colorValidationStateIsError)
    //         }
    //     },
    //     [inputColorG]
    // )
    // useEffect(
    //     () => {
    //         const isValidationOk = Number(inputColorB).toString(16).match(/^[\da-fA-F]{1,2}$/)
    //         if(isValidationOk) {
    //             const left  = acceptedColorCode.slice(0,5)
    //             const right = ''
    //             const paddedHex = ('00' + Number(inputColorB).toString(16)).slice(-2)
    //             const neewColorCode = left + paddedHex + right
    //             setAcceptedColorCode(neewColorCode)
    //             setInputColorCode(neewColorCode)

    //             setColorValidationState(colorValidationStateIsAccepted)
    //         }
    //         else {
    //             setColorValidationState(colorValidationStateIsError)
    //         }
    //     },
    //     [inputColorB]
    // )





                        //     { <ColorValidation
                        //     className={inUseColor === 0 ? "" : "d-none"}
                        //     acceptedColorCode={acceptedColorCode00}
                        //     setAcceptedColorCode={setAcceptedColorCode00}
                        // />
                        // <ColorValidation
                        //     className={inUseColor === 1 ? "" : "d-none" }
                        //     acceptedColorCode={acceptedColorCode01}
                        //     setAcceptedColorCode={setAcceptedColorCode01}
                        // /> }