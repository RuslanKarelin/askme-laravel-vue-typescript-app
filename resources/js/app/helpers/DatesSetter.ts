import { DateTime } from "luxon";

const setDates = (element: any, dates: string[]) => {
  dates.forEach((fieldName: string) => {
      if (element[fieldName]) {
          element[fieldName] = DateTime.fromISO(element[fieldName]).setLocale("en");
      }
  })
};

export {setDates}