function GetHexColor(decR = 0, decG = 0, decB = 0) {
    const hexR = Number(decR).toString(16)
    const hexG = Number(decG).toString(16)
    const hexB = Number(decB).toString(16)

    const paddedHexR = ('00' + hexR).slice(-2)
    const paddedHexG = ('00' + hexG).slice(-2)
    const paddedHexB = ('00' + hexB).slice(-2)

    return paddedHexR + paddedHexG + paddedHexB
}

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

export { GetHexColor, GetFetchData}