import React, { useRef } from 'react'

function ColorSelector(props) {
    const colorOptions = Array(256).fill(0)
    const selectRef = useRef(null)

    colorOptions.forEach((colorOption, index) => (
        colorOptions.push( <option value= {index} key={index}>{index}</option> )
    ))

    function handleChange(event) {
        props.onChange(event.target.value)
        //console.log(event.target.value)
    }

    return (
        <select ref={selectRef} onChange={handleChange}>
            {colorOptions}
        </select>
    )
}

export { ColorSelector }