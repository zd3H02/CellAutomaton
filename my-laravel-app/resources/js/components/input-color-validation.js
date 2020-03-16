import React, { useState, useRef, useEffect } from 'react'
import ReactDOM from 'react-dom'


function InputValidation(input, regex, isCheckExecute, setValidationOkState, setValidationNgState, acceptProcess) {
    useEffect(
        () => {
            const isValidationOk = input.match(regex)
            if(isCheckExecute) {
                if(isValidationOk) {
                    setValidationOkState()
                    acceptProcess()
                }
                else {
                    setValidationNgState()
                }
            }
        },
        [input]
    )
}


export { InputValidation }