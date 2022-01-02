// ==UserScript==
// @name         Niagara Covid Dashboard Data Fetch
// @namespace    http://niagara.krunk.cn/more-data.php
// @version      0.1
// @description  try to take over the world!
// @author       Krunk
// @match        https://app.powerbi.com/*
// @grant        none
// ==/UserScript==

setTimeout(function(){
    function getElementByXpath(path) {
        return document.evaluate(path, document, null, XPathResult.FIRST_ORDERED_NODE_TYPE, null).singleNodeValue;
    }
    console.log("查询中...");
    var strCaseNumbers=(document.querySelector("#pvExplorationHost > div > div > exploration > div > explore-canvas > div > div.canvasFlexBox > div > div.displayArea.disableAnimations.fitToWidthOrigin > div.visualContainerHost > visual-container-repeat > visual-container:nth-child(9) > transform > div > div:nth-child(4) > div > visual-modern > div > svg > g:nth-child(1) > text > tspan")
                        .innerHTML.toString());
    console.log("确诊: "+parseFloat(strCaseNumbers.replace(/,/g, '')));
    var spnResolvedCases=(document.querySelector("#pvExplorationHost > div > div > exploration > div > explore-canvas > div > div.canvasFlexBox > div > div.displayArea.disableAnimations.fitToWidthOrigin > div.visualContainerHost > visual-container-repeat > visual-container:nth-child(11) > transform > div > div:nth-child(4) > div > visual-modern > div > svg > g:nth-child(1) > text > tspan")
                          .innerHTML.toString());
    console.log("康复: "+parseFloat(spnResolvedCases.replace(/,/g, '')));
    var dNum=(document.querySelector("#pvExplorationHost > div > div > exploration > div > explore-canvas > div > div.canvasFlexBox > div > div.displayArea.disableAnimations.fitToWidthOrigin > div.visualContainerHost > visual-container-repeat > visual-container:nth-child(10) > transform > div > div:nth-child(4) > div > visual-modern > div > svg > g:nth-child(1) > text > tspan")
              .innerHTML.toString());
    console.log("死亡: "+parseFloat(dNum.replace(/,/g, '')));
    console.log("Sending...");
    $.get('https://niagara.krunk.cn/update-from-js.php?api=t&strCaseNumbers='+parseFloat(strCaseNumbers.replace(/,/g, ''))+'&spnResolvedCases='+parseFloat(spnResolvedCases.replace(/,/g, ''))+'&dNum='+parseFloat(dNum.replace(/,/g, '')), {
    }, function(res) {
        console.log(res);
    });

}, 25*600);

var timer=Math.random()*600*1000;
setTimeout(function(){ location.reload(); }, timer);
console.log("Timer: "+timer/1000+" sec");