const myHeaders = new Headers();
myHeaders.append("Content-Type", "application/json");

const raw = JSON.stringify({
  "id": 92704,
  "gateway": "Vietcombank",
  "transactionDate": "2023-03-25 14:02:37",
  "accountNumber": "0123499999",
  "code": null,
  "content": "PKG17533010901",
  "transferType": "in",
  "transferAmount": 260,
  "accumulated": 19077000,
  "subAccount": null,
  "referenceCode": "PKG17533010901",
  "description": ""
});

const requestOptions = {
  method: "POST",
  headers: myHeaders,
  body: raw,
  redirect: "follow"
};

fetch("http://quizz-onl.local/sepay-webhook.php", requestOptions)
  .then((response) => response.text())
  .then((result) => console.log(result))
  .catch((error) => console.error(error));