/*Margallo*/
/*Convert table to excel*/


async function exportTable() {
    const table = document.getElementById("table");

    let rowIndex = 1;

    // Create a new workbook and add a worksheet
    const workbook = new ExcelJS.Workbook();
    const worksheet = workbook.addWorksheet("Attendance Summary"); // Define the worksheet within this function

    // Mapping of background colors to corresponding Excel fill colors
    const colorMapping = {
        "rgb(0, 255, 0)": "00FF00",
        "rgb(255, 0, 0)": "FF0000",
        "rgb(247, 87, 87)": "F75757",
        "rgb(106, 255, 102)": "6AFF66"
    };

    // Default fill color for cells with no specified background color
    const defaultFillColor = "ffffff"; // White

    // Default border color for cells
    const borderColor = "808080";

    // Loop through table rows and cells to add data to the worksheet
    table.querySelectorAll("tr").forEach((rowElement) => {
        const rowData = [];

        // Create a new row in the worksheet for each table row
        const excelRow = worksheet.addRow([]);

        rowElement.querySelectorAll("td, th").forEach((cell, columnIndex) => {
            const cellData = cell.innerText;

            // Extract the cell background color
            const cellComputedStyle = window.getComputedStyle(cell);
            const cellBackgroundColor = cellComputedStyle.backgroundColor;

            // Determine the fill color for the cell
            let fillColor = defaultFillColor; // Default to white

            if (cellBackgroundColor) {
                // Check if the cell background color matches a defined color mapping
                fillColor = colorMapping[cellBackgroundColor] || defaultFillColor;
            }

            if (rowIndex === 1) {
                if (columnIndex < 1) {
                    // Add empty cells for the first three columns in the first row
                    excelRow.getCell(columnIndex + 1).value = ''; // Empty cell
                } else {
                    excelRow.getCell(columnIndex * 2 + 3).value = cellData;
                    excelRow.getCell(columnIndex * 2 + 4).value = ''; // Empty cell for merging
                    worksheet.mergeCells(rowIndex, columnIndex * 2 + 3, rowIndex, columnIndex * 2 + 4);
                }
            } else {
                // For other rows, start in column 2
                const excelCell = excelRow.getCell(columnIndex + 2);
                excelCell.value = cellData;
            }

            // Apply the fill color to the Excel cell
            if (rowIndex === 1) {
                excelRow.getCell(columnIndex * 2 + 3 + 2).fill = {
                    type: "pattern",
                    pattern: "solid",
                    fgColor: {
                        argb: fillColor,
                    },
                };
                 // Bold the first row's font
                 excelRow.getCell(columnIndex * 2 + 3 + 2).font = {
                    bold: true,
                };
            } else {
                excelRow.getCell(columnIndex + 2 ).fill = {
                    type: "pattern",
                    pattern: "solid",
                    fgColor: {
                        argb: fillColor,
                    },
                };
            }

            // Apply black borders to the cell
            if (rowIndex === 1) {
                excelRow.getCell(columnIndex * 2 + 3 + 2).border = {
                    top: { style: "thin", color: { argb: borderColor } },
                    left: { style: "thin", color: { argb: borderColor } },
                    bottom: { style: "thin", color: { argb: borderColor } },
                    right: { style: "thin", color: { argb: borderColor } },
                };
            } else {
                excelRow.getCell(columnIndex + 2 ).border = {
                    top: { style: "thin", color: { argb: borderColor } },
                    left: { style: "thin", color: { argb: borderColor } },
                    bottom: { style: "thin", color: { argb: borderColor } },
                    right: { style: "thin", color: { argb: borderColor } },
                };
            }

            rowData.push(cellData);
        });


        rowData.forEach((cellData, columnIndex) => {
            const column = worksheet.getColumn(columnIndex + 1); // +1 because Excel columns are 1-based

            if (columnIndex === 0) {
                column.width = 0; // Set the width in characters for column A
            } else if (columnIndex === 1) {
                column.width = 30; // Set the width in characters for column B
            } else if (columnIndex === 2) {
                column.width = 20; // Set the width in charactersfor column C
            } else if(columnIndex === 3){
                column.width = 20; 
            }else{
                column.width = 5;
            }
        });

        rowIndex++;
    });

    const filename = `Attendance Summary.xlsx`;

    // Create a Blob containing the Excel data
    const blob = await workbook.xlsx.writeBuffer();

    // Create a temporary link element to trigger the download
    const link = document.createElement("a");
    link.href = URL.createObjectURL(new Blob([blob], { type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" }));
    link.download = filename; // Use the generated filename
    link.click();
}

async function exportDTR(userName, userPosition, userDepartment) {
    const table = document.getElementById("table");

    // Create a new workbook
    const workbook = new ExcelJS.Workbook();
    
    // Add a worksheet
    const worksheet = workbook.addWorksheet("Daily Time Record");

   
    // Array to store the table data
    const tableData = [];

    // Iterate through the table rows and cells, starting from row 0
    for (let i = 0; i < table.rows.length; i++) {
        const row = table.rows[i];
        const rowData = [];
        for (let j = 0; j < row.cells.length; j++) {
            const cell = row.cells[j];
            rowData.push(cell.innerText);
        }
        tableData.push(rowData);
    }

    // Define columnStyles object
    const columnStyles = {
        B: { alignment: { horizontal: 'center' } },
        C: { alignment: { horizontal: 'center' } },
        D: { alignment: { horizontal: 'center' } },
        E: { alignment: { horizontal: 'center' } },
        F: { alignment: { horizontal: 'center' } },
    };

    // Apply styles to the header row (B6:F6)
    for (let colIndex = 0; colIndex <= 4; colIndex++) {
        const cell = worksheet.getCell(String.fromCharCode(66 + colIndex) + '6');
        cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: '92D050' } };
        cell.alignment = { horizontal: 'center' };
    }

    // Set column dimensions and styles
    worksheet.columns.forEach((column, index) => {
        const columnLetter = String.fromCharCode(65 + index); 
        if (columnStyles[columnLetter]) {
            column.width = 15; 
            column.alignment = columnStyles[columnLetter].alignment;
        }
    });

    // Add the table data to the Excel worksheet starting from B6
    const startRow = 6;
    for (let rowIndex = 0; rowIndex < tableData.length; rowIndex++) {
        const rowData = tableData[rowIndex];
        for (let colIndex = 0; colIndex < rowData.length; colIndex++) {
            const cellValue = rowData[colIndex];
            const cell = worksheet.getCell(String.fromCharCode(66 + colIndex) + (startRow + rowIndex));
            cell.value = cellValue;
        }
    }

    // Add borders to the table
    const endRow = startRow + tableData.length; // End row of the table
    const startColumn = 'B'; // Start column of the table
    const endColumn = String.fromCharCode(65 + tableData[0].length); // End column of the table

    // Loop through the cells in the specified range and add borders
    for (let rowIndex = startRow; rowIndex <= endRow; rowIndex++) {
        for (let colIndex = startColumn.charCodeAt(0); colIndex <= endColumn.charCodeAt(0); colIndex++) {
            const cell = worksheet.getCell(String.fromCharCode(colIndex) + rowIndex);
            cell.border = {
                top: { style: 'thin' },
                left: { style: 'thin' },
                bottom: { style: 'thin' },
                right: { style: 'thin' },
            };
        }
    }

    // Set values for cells B2, B3, and B4
     worksheet.getCell('B2').value = userName;
     worksheet.getCell('B3').value = userDepartment;
     worksheet.getCell('B4').value = userPosition;
 
     const fontColor = '76933C';
 
     // Set font color and alignment for cells B2, B3, and B4
     worksheet.getCell('B2').alignment = { horizontal: 'left' };
     worksheet.getCell('B3').alignment = { horizontal: 'left' };
     worksheet.getCell('B4').alignment = { horizontal: 'left' };
 
     // Set values for cells A2, A3, and A4
     worksheet.getCell('A2').value = 'Name';
     worksheet.getCell('A3').value = 'Department';
     worksheet.getCell('A4').value = 'Position';
     
     // Apply font color to cells A2, A3, and A4
     worksheet.getCell('A2').font = { color: { argb: fontColor } };
     worksheet.getCell('A3').font = { color: { argb: fontColor } };
     worksheet.getCell('A4').font = { color: { argb: fontColor } };
 
     worksheet.getColumn('A').width = 15;
     worksheet.getColumn('B').width = 30;
     worksheet.getColumn('E').width = 10;
     worksheet.getColumn('F').width = 15;

    const filename = `Daily Time Record.xlsx`;

    // Create a Blob containing the Excel data
    const blob = await workbook.xlsx.writeBuffer();

    // Create a temporary link element to trigger the download
    const link = document.createElement("a");
    link.href = URL.createObjectURL(new Blob([blob], { type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" }));
    link.download = filename; 
    link.click();
}

async function exportTable_gian() {
    const table = document.getElementById("table");

    let rowIndex = 1;

    // Create a new workbook and add a worksheet
    const workbook = new ExcelJS.Workbook();
    const worksheet = workbook.addWorksheet("View Time In and Out"); // Define the worksheet within this function

    // Mapping of background colors to corresponding Excel fill colors
    const colorMapping = {
        "rgb(0, 255, 0)": "00FF00",
        "rgb(255, 0, 0)": "FF0000",
        "rgb(247, 87, 87)": "F75757",
        "rgb(106, 255, 102)": "6AFF66"
    };

    // Default fill color for cells with no specified background color
    const defaultFillColor = "ffffff"; // White

    // Default border color for cells
    const borderColor = "808080";

    // Loop through table rows and cells to add data to the worksheet
    table.querySelectorAll("tr").forEach((rowElement) => {
        const rowData = [];

        // Create a new row in the worksheet for each table row
        const excelRow = worksheet.addRow([]);

        rowElement.querySelectorAll("td, th").forEach((cell, columnIndex) => {
            const cellData = cell.innerText;

            // Extract the cell background color
            const cellComputedStyle = window.getComputedStyle(cell);
            const cellBackgroundColor = cellComputedStyle.backgroundColor;

            // Determine the fill color for the cell
            let fillColor = defaultFillColor; // Default to white

            if (cellBackgroundColor) {
                // Check if the cell background color matches a defined color mapping
                fillColor = colorMapping[cellBackgroundColor] || defaultFillColor;
            }

            if (rowIndex === 1) {
                if (columnIndex < 1) {
                    // Add empty cells for the first three columns in the first row
                    excelRow.getCell(columnIndex + 2).value = ''; // Empty cell
                } else {
                    excelRow.getCell(columnIndex + 2).value = cellData;
                    //excelRow.getCell(columnIndex * 2 + 4).value = ''; // Empty cell for merging
                    //worksheet.mergeCells(rowIndex, columnIndex * 2 + 3, rowIndex, columnIndex * 2 + 4);
                }
            } else {
                // For other rows, start in column 2
                const excelCell = excelRow.getCell(columnIndex + 2);
                excelCell.value = cellData;
            }

            // Apply the fill color to the Excel cell
            if (rowIndex === 1) {
                excelRow.getCell(columnIndex + 2).fill = {
                    type: "pattern",
                    pattern: "solid",
                    fgColor: {
                        argb: fillColor,
                    },
                };
                 // Bold the first row's font
                 excelRow.getCell(columnIndex + 2).font = {
                    bold: true,
                };
            } else {
                excelRow.getCell(columnIndex + 2 ).fill = {
                    type: "pattern",
                    pattern: "solid",
                    fgColor: {
                        argb: fillColor,
                    },
                };
            }

            // Apply black borders to the cell
            if (rowIndex === 1) {
                excelRow.getCell(columnIndex + 2).border = {
                    top: { style: "thin", color: { argb: borderColor } },
                    left: { style: "thin", color: { argb: borderColor } },
                    bottom: { style: "thin", color: { argb: borderColor } },
                    right: { style: "thin", color: { argb: borderColor } },
                };
            } else {
                excelRow.getCell(columnIndex + 2 ).border = {
                    top: { style: "thin", color: { argb: borderColor } },
                    left: { style: "thin", color: { argb: borderColor } },
                    bottom: { style: "thin", color: { argb: borderColor } },
                    right: { style: "thin", color: { argb: borderColor } },
                };
            }

            rowData.push(cellData);
        });


        rowData.forEach((cellData, columnIndex) => {
            const column = worksheet.getColumn(columnIndex + 1); // +1 because Excel columns are 1-based

            if (columnIndex === 0) {
                column.width = 0; // Set the width in characters for column A
            } else if (columnIndex === 1) {
                column.width = 30; // Set the width in characters for column B
            } else if (columnIndex === 2) {
                column.width = 20; // Set the width in charactersfor column C
            } else if(columnIndex === 3){
                column.width = 20; 
            }else{
                column.width = 5;
            }
        });

        rowIndex++;
    });

    const filename = `View Time In and Out.xlsx`;

    // Create a Blob containing the Excel data
    const blob = await workbook.xlsx.writeBuffer();

    // Create a temporary link element to trigger the download
    const link = document.createElement("a");
    link.href = URL.createObjectURL(new Blob([blob], { type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" }));
    link.download = filename; // Use the generated filename
    link.click();
}


/*Margallo*/
