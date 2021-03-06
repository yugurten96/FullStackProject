import React from 'react';
import DatePicker from "react-datepicker";

const DatePick = ({date, setDate, minDate, type}) => {
  const years = [2017, 2018, 2019, 2020, 2021]
  const months = [
    "January",
    "February",
    "March",
    "April",
    "May",
    "June",
    "July",
    "August",
    "September",
    "October",
    "November",
    "December",
  ];

  return (
    <div>
      <DatePicker
        renderCustomHeader={({
                               date,
                               changeYear,
                               changeMonth,
                               decreaseMonth,
                               increaseMonth,
                               prevMonthButtonDisabled,
                               nextMonthButtonDisabled,
                             }) => (
          <div
            style={{
              margin: 10,
              display: "flex",
              justifyContent: "center",
            }}
          >
            <button onClick={decreaseMonth} disabled={prevMonthButtonDisabled}>
              {"<"}
            </button>
            <select
              value={date.getFullYear()}
              onChange={({target: {value}}) => changeYear(value)}
            >
              {years.map((option) => (
                <option key={option} value={option}>
                  {option}
                </option>
              ))}
            </select>

            <select
              value={months[date.getMonth()]}
              onChange={({target: {value}}) =>
                changeMonth(months.indexOf(value))
              }
            >
              {months.map((option) => (
                <option key={option} value={option}>
                  {option}
                </option>
              ))}
            </select>

            <button onClick={increaseMonth} disabled={nextMonthButtonDisabled}>
              {">"}
            </button>
          </div>
        )}

        minDate={minDate}
        selected={date}
        selectsStart={type === "start"}
        selectsEnd={type === "end"}
        onChange={(date) => setDate(date)}
      />
    </div>
  );
};

export default DatePick;
