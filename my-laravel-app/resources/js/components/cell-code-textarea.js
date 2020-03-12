import React, { useRef } from 'react'

function CellCodeTextarea(props) {
    const selectRef = useRef(null)

    function handleChange(event) {
        props.onChange(event.target.value)
        console.log(event.target.value)
    }
    const tstyle ={
        opacity: 0
    }
    return (
        <textarea
            ref={selectRef}
            onChange={handleChange}
            value={props.value}
            name="code"
            id="code"
            cols="30"
            rows="10"
        >
        </textarea>
    )
}

export { CellCodeTextarea }




// const divStyle = {
//     position: 'relative',
// }
// const textareaStyle = {
//     heigth: '100px',
//     width: '100px',
//     position: 'absolute',
//     // color: 'transparent',
//     // backgroundcolor: 'transparent',
//     opacity: 0
// }
// const syntaxHighlighterStyle = {
//     heigth: '100px',
//     width: '100px',
//     position: 'absolute',

// }

    // <form method="POST">
    //     @csrf
    //     <textarea name="code" id="code" cols="30" rows="10"></textarea>
    //     <button id="run_button" type="submit" formaction="{{ url('local/run')}}" name="run" value="true">実行</button>
    //     <button id="run_button" type="submit" formaction="{{ url('local/stop')}}" name="stop" value="true">停止</button>
    //     <button id="run_button" type="submit" formaction="{{ url('local/save')}}" name="save" value="true">保存</button>
    // </form>



// import React, { useRef } from 'react'

// function ColorSelector(props) {
//     const colorOptions = Array(256).fill(0)
//     const selectRef = useRef(null)

//     colorOptions.forEach((colorOption, index) => (
//         colorOptions.push( <option value= {index} key={index}>{index}</option> )
//     ))

//     function handleChange(event) {
//         props.onChange(event.target.value)
//         console.log(event.target.value)
//     }

//     return (
//         <select ref={selectRef} onChange={handleChange}>
//             {colorOptions}
//         </select>
//     )
// }

// export { ColorSelector }