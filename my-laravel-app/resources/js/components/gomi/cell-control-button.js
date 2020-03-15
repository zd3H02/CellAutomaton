import React, { useRef } from 'react'

function CellControlButton(props) {
    const selectRef = useRef(null)

    function handleChange(event) {
        props.onChange(event.target.value)
        //console.log(event.target.value)
    }

    return (
    <button ref={selectRef} onClick={handleChange} value={props.value}>{props.content}</button>
    )
}

export { CellControlButton }


    //     <button id="run_button" type="submit" formaction="{{ url('local/run')}}" name="run" value="true">実行</button>
    //     <button id="run_button" type="submit" formaction="{{ url('local/stop')}}" name="stop" value="true">停止</button>
    //     <button id="run_button" type="submit" formaction="{{ url('local/save')}}" name="save" value="true">保存</button>