/**
 * @template T
 * @template Y
 * @param {T[]} arr
 * @param {(element:T) => Y} func
 * @returns {{[x in Y]: Array<T>}}
 */
export default function arrayGroupBy(arr, func) {  
   /**@type {{[x in Y]: Array<T>}} */   
   // @ts-ignore
   let final_obj = {};

   arr.forEach((el) => {
      let key = func(el)

      if (final_obj[key] == undefined) {
         final_obj[key] = []
      }

      final_obj[key].push(el)
   })
   
   return final_obj
}
