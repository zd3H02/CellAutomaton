import React from 'react'
// import { GetHexColor, GetFetchData} from './utility'

function Cell(props) {
    const btnStyle = {
        height : '40px',
        width  : '40px',
        background : props.color,
    }
    return (
        <button onClick={()=>props.onClick()} style={btnStyle} className={"btn btn-default"}/>
    )
}

function CellMatrix(props) {
    function HandleClick(i, color) {
        const newCellColors = props.cellColors.slice();
        newCellColors[i] = color
        props.setCellColors(newCellColors)
        console.log(i)
        console.log(color)
    }

    const tempCells = Array(props.MAX_CELL_ROW_NUM).fill([])
    for(let i = 0; i < props.MAX_CELL_ROW_NUM; i++) {
        tempCells[i] = Array(props.MAX_CELL_COL_NUM).fill(0)
    }

    const cells = tempCells.map((rows, rowI) =>
        <div key = {rowI.toString()}>
            {rows.map((col, colI) =>
                <Cell
                    key = {colI.toString()}
                    onClick = {
                        ()=>HandleClick(
                            props.MAX_CELL_COL_NUM * rowI + colI,
                            props.acceptedColorCode
                        )
                    }
                    color = {props.cellColors[props.MAX_CELL_COL_NUM * rowI + colI]}
                />
            )}
        </div>
    )
    // console.log(cells)

    return (
        <div>{cells}</div>
    )
}

export { Cell, CellMatrix }