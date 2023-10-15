require("dotenv").config();
const { Client } = require("@threadsjs/threads.js");
const CronJob = require("cron").CronJob;
const express = require("express");
const app = express();
const axios = require("axios");
const bodyParser = require("body-parser");
const port = process.env.PORT;

app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: false }));

app.listen(port, () => {
  console.log(`Listening on port ${port}`);
});

async function earthquake(messageEarthquake) {
  const client = new Client();
  // You can also specify a token: const client = new Client({ token: 'token' });
  await client.login(process.env.IG_USERNAME, process.env.IG_PASSWORD);

  await client.posts.create(1, { contents: messageEarthquake });
}

app.post("/", (req, res) => {
  const data = req.body;
  earthquake(data.earthquakethreads.toString());
  res.send(data.earthquakethreads.toString());
});

const cronThreads = new CronJob("10 * * * * *", async () => {
  // with Open Browser
  // require("child_process").exec(
  // 	"start http://localhost/gempa-threads-bot/gempa"
  // );
  // with Open Browser

  axios
    .get("http://localhost/gempa-threads-bot/gempa")
    .then(function (res) {
      // console.log(res);
      console.log("success");
    })
    .catch(function (error) {
      console.log(error);
    });
});

cronThreads.start();
