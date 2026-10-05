import {Ajax} from "../../Core/js/ajax";
import {pageManager} from "../../Core/js/pageManager";

pageManager.onLoad((_0,_1,initInfo)=>{
    Ajax.FrontendLogCollector.logJsInit(initInfo.pageOpenIdentifier)
})
