import { defineStore } from "pinia";
import axios from "axios";

export const useCreateRepertoire = defineStore('createRepertoire',{
    
    state: () => ({}),
    actions: {
        get(url,data, config){
            return axios.get(url,data,config)   
        },
        insert(url, data, config){
            return axios.post(url, data, config)
        },
        generate(url, data, config){
            return axios.post(url, data, config)
        },
        update(url, data, config){
            return axios.post(url, data, config)
        },
        delete(url, data, config){
            return  axios.post(url, data, config)
        }
    }
    
});