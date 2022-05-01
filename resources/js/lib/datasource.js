import {$get, $post} from "../utils";

let requestQueue = [];
const promiseMap = {};
let timeoutId;
let DataSourceFirstInit = true;
let autoID = 1;

export function $getDataSource(datasource, params = {}, progress = true) {
    if (!DataSourceFirstInit) {
        return $get('/xadmin/data-source/index?datasource=' + datasource, params, false);
    }

    return new Promise(((resolve, reject) => {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(async () => {
            let entries = requestQueue;
            requestQueue = [];

            const res = await $post('/xadmin/data-source/get-many', {
                entries
            });

            setTimeout(() => {
                DataSourceFirstInit = false;
            }, 500);

            if (res.code === 200) {
                const result = res.result;
                for (let reqId in result) {
                    if (result.hasOwnProperty(reqId)) {
                        const e = promiseMap[reqId];
                        const rRes = result[reqId];

                        if (rRes.code === 200) {
                            e.resolve(rRes);
                        } else {
                            console.error(rRes);
                            e.reject(rRes);
                            //toastr.error(`DataSource ${reqId} Error: ` +  rRes.message)
                        }

                    }
                }
                promiseMap.forEach((e, i) => {
                    e.resolve(result[i]);
                });
            }

        }, 500);

        const requestId = autoID;
        requestQueue.push({request_id: requestId, datasource, params});
        promiseMap[requestId] = {resolve, reject};
        autoID++;
    }));
}
