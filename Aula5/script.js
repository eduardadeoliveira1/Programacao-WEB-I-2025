function calcular() {
const num1 = parseFloat(document.getElementById('num1').value);
const num2 = parseFloat(document.getElementById('num2').value);
const op = document.getElementById('op').value;
const resultadoDiv = document.getElementById('resultado');


let resultado;
if (op === 'add') resultado = num1 + num2;
else if (op === 'sub') resultado = num1 - num2;
else if (op === 'mul') resultado = num1 * num2;
else if (op === 'div') {
if (num2 === 0) {
resultadoDiv.textContent = 'Erro: divisão por 0';
resultadoDiv.style.background = 'red';
return;
}
resultado = num1 / num2;
}


resultadoDiv.textContent = resultado;
if (resultado > 0) resultadoDiv.style.background = 'green';
else if (resultado < 0) resultadoDiv.style.background = 'red';
else resultadoDiv.style.background = 'gray';
}