function sortByAge(arr, key) {
  arr.sort((a, b) => (a[key] > b[key] ? 1 : -1));
}

let vasya = { name: "Вася", age: 25 };
let petya = { name: "Петя", age: 30 };
let masha = { name: "Маша", age: 28 };

let arr = [vasya, petya, masha];

console.log(arr, "age");
sortByAge(arr);
console.log(arr);
