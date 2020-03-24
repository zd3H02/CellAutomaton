import React, { useState, useEffect } from 'react'

function ColorValidation (props) {
    // const [inputColorCode, setInputColorCode] = useState('#000000')
    // const [inputColorR, setInputColorR] = useState(0)
    // const [inputColorG, setInputColorG] = useState(0)
    // const [inputColorB, setInputColorB] = useState(0)

    const [inputColorCode, setInputColorCode] = useState(props.acceptedColorCode)
    const [inputColorR, setInputColorR] = useState(props.acceptedColorCode.slice(1,3))
    const [inputColorG, setInputColorG] = useState(props.acceptedColorCode.slice(3,5))
    const [inputColorB, setInputColorB] = useState(props.acceptedColorCode.slice(5,7))

    const colorValidationStateIsAccepted = 'Accepted'
    const colorValidationStateIsError    = 'Error'
    const [colorValidationState, setColorValidationState] = useState(colorValidationStateIsAccepted)

    useEffect(
        () => {
            const isValidationOk = inputColorCode.match(/^#[\da-fA-F]{6}$/)
            if(isValidationOk) {
                props.setAcceptedColorCode(inputColorCode)
                setInputColorR(parseInt(inputColorCode.slice(1,3),16))
                setInputColorG(parseInt(inputColorCode.slice(3,5),16))
                setInputColorB(parseInt(inputColorCode.slice(5,7),16))
                setColorValidationState(colorValidationStateIsAccepted)
            }
            else {
                setColorValidationState(colorValidationStateIsError)
            }
        },
        [inputColorCode]
    )
    useEffect(
        () => {
            const isValidationOk = Number(inputColorR).toString(16).match(/^[\da-fA-F]{1,2}$/)
            if(isValidationOk) {
                const left  = props.acceptedColorCode.slice(0,1)
                const right = props.acceptedColorCode.slice(3)
                const paddedHex = ('00' + Number(inputColorR).toString(16)).slice(-2)
                const newColorCode = left + paddedHex + right
                props.setAcceptedColorCode(newColorCode)
                setInputColorCode(newColorCode)
                setColorValidationState(colorValidationStateIsAccepted)
            }
            else {
                setColorValidationState(colorValidationStateIsError)
            }
        },
        [inputColorR]
    )
    useEffect(
        () => {
            const isValidationOk = Number(inputColorG).toString(16).match(/^[\da-fA-F]{1,2}$/)
            if(isValidationOk) {
                const left  = props.acceptedColorCode.slice(0,3)
                const right = props.acceptedColorCode.slice(5)
                const paddedHex = ('00' + Number(inputColorG).toString(16)).slice(-2)
                const newColorCode = left + paddedHex + right
                props.setAcceptedColorCode(newColorCode)
                setInputColorCode(newColorCode)
                setColorValidationState(colorValidationStateIsAccepted)
            }
            else {
                setColorValidationState(colorValidationStateIsError)
            }
        },
        [inputColorG]
    )
    useEffect(
        () => {
            const isValidationOk = Number(inputColorB).toString(16).match(/^[\da-fA-F]{1,2}$/)
            if(isValidationOk) {
                const left  = props.acceptedColorCode.slice(0,5)
                const right = ''
                const paddedHex = ('00' + Number(inputColorB).toString(16)).slice(-2)
                const newColorCode = left + paddedHex + right
                props.setAcceptedColorCode(newColorCode)
                setInputColorCode(newColorCode)
                setColorValidationState(colorValidationStateIsAccepted)
            }
            else {
                setColorValidationState(colorValidationStateIsError)
            }
        },
        [inputColorB]
    )
    return (
        <div className={props.className} style={props.style}>
            <p className="p-0 m-0">
                <label htmlFor="Hex">Hex:</label>
                <input id={"Hex" + props.i} className="mr-1 align-middle" type="text" value={inputColorCode} onChange={event=>setInputColorCode(event.target.value)}/>
                <input type="range" className="mr-1 align-middle" value={parseInt(inputColorCode.slice(1),16)} onChange={event=>setInputColorCode('#'+('000000'+Number(event.target.value).toString(16)).slice(-6))} min="0" max="16777215"/>
            </p>
            <p className="p-0 m-0">
                <label htmlFor="R">&nbsp;&nbsp;&nbsp;&nbsp;R:</label>
                <input id={"R" + props.i} className="mr-1 align-middle" type="text" value={inputColorR} onChange={event=>setInputColorR(event.target.value)}/>
                <input type="range" className="mr-1 align-middle" value={inputColorR} onChange={event=>setInputColorR(event.target.value)} min="0" max="255"/>
            </p>
            <p className="p-0 m-0">
                <label htmlFor="G">&nbsp;&nbsp;&nbsp;&nbsp;G:</label>
                <input id={"G" + props.i} className="mr-1 align-middle" type="text" value={inputColorG} onChange={event=>setInputColorG(event.target.value)}/>
                <input type="range" className="mr-1 align-middle" value={inputColorG} onChange={event=>setInputColorG(event.target.value)} min="0" max="255"/>
            </p>
            <p className="p-0 m-0">
                <label htmlFor="B">&nbsp;&nbsp;&nbsp;&nbsp;B:</label>
                <input id={"B" + props.i} className="mr-1 align-middle" type="text" value={inputColorB} onChange={event=>setInputColorB(event.target.value)}/>
                <input type="range" className="mr-1 align-middle" value={inputColorB} onChange={event=>setInputColorB(event.target.value)} min="0" max="255"/>
            </p>
        </div>
    )
}



export { ColorValidation }