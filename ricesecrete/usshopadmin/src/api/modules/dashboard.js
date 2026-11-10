import {get} from '@/libs/axios'

const dashboard = {
    // 统计
    statistics(formatData){
        return get('/dashboard/statistics',formatData)
    }
}

export default dashboard
