import React, { useState } from 'react'
// import { GetHexColor, GetFetchData} from './utility'

import {
    Container,
    Row,
    Col,
    Button,
} from 'react-bootstrap'

function Cell(props) {
    const btnStyle = {
        height : '40px',
        width  : '40px',
        background : props.color,
    }
    return (
        <Button
            // onClick={()=>props.onClick()}
            onMouseOver={()=>props.onMouseOver()}
            onMouseDown={()=>props.onMouseDown()}
            onMouseUp={()=>props.onMouseUp()}
            style={btnStyle}
            className="btn btn-default p-0 border border-0"/>
    )
}

function CellMatrix(props) {
    const mouseStateIsDown = 'Down'
    const mouseStateIsUp   = 'Up'
    const [mouseState, setMouseState] = useState(mouseStateIsUp)

    // function HandleClick(i, color) {
    //     // const newCellColors = props.cellColors.slice();
    //     // newCellColors[i] = color
    //     // props.setCellColors(newCellColors)
    //     // console.log(i)
    //     // console.log(color)
    // }

    function HandleMouseOver(i, color){
        if(mouseState === mouseStateIsDown) {
            const newCellColors = props.cellColors.slice();
            newCellColors[i] = color
            props.setCellColors(newCellColors)
            console.log(i)
            console.log(color)
        }
    }

    function HandleMouseDown(i, color) {
        const newCellColors = props.cellColors.slice();
        newCellColors[i] = color
        props.setCellColors(newCellColors)
        console.log(i)
        console.log(color)
        setMouseState(mouseStateIsDown)
    }

    const tempCells = Array(props.MAX_CELL_ROW_NUM).fill([])
    for(let i = 0; i < props.MAX_CELL_ROW_NUM; i++) {
        tempCells[i] = Array(props.MAX_CELL_COL_NUM).fill(0)
    }

    const cells = tempCells.map((rows, rowI) =>
            <p key = {rowI.toString()} className={"m-0"}>
                {rows.map((col, colI) =>
                    <Cell
                        key = {colI.toString()}
                        // onClick = {
                        //     ()=>HandleClick(
                        //         props.MAX_CELL_COL_NUM * rowI + colI,
                        //         props.acceptedColorCode
                        //     )
                        // }
                        onMouseOver={
                            ()=>HandleMouseOver(
                                props.MAX_CELL_COL_NUM * rowI + colI,
                                props.acceptedColorCode,
                                // mouseState
                            )
                        }
                        // onMouseDown={()=>setMouseState(mouseStateIsDown)}
                        onMouseDown={
                            ()=>HandleMouseDown(
                                props.MAX_CELL_COL_NUM * rowI + colI,
                                props.acceptedColorCode
                            )
                        }
                        onMouseUp={()=>setMouseState(mouseStateIsUp)}
                        color = {props.cellColors[props.MAX_CELL_COL_NUM * rowI + colI]}
                    />
                )}
            </p>

    )
    // console.log(cells)

    return (
        <div className={props.className}>{cells}</div>
    )
}

export { Cell, CellMatrix }