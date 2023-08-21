const express = require("express");
const app = express();

app.listen(3000, () => {
  console.log("Application started and Listening on port 3000");
});

app.get("/books", (req, res) => {

  const getData = async () => {
    const res = await fetch('http://api.localhost/books?page=1&itemsPerPage=30');
    if(res.ok){
      return await res.json();
      // console.log(data);
    } else {
      console.log('error');
    }
  }

  const data = getData();

  data.then(data => {
    const jsonContent = JSON.stringify(data);
    res.setHeader('Content-Type', 'application/json');
    res.end(jsonContent);
  } );

  // res.end(jsonContent);
  // res.send(data);
});

app.get("/", (req, res) => {
  res.sendFile(__dirname + "/index.html");
});


