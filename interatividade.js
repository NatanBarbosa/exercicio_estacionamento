function ocuparVaga(vaga, tag) {
    //verificar se está ocupada ou desocupada
    let status = tag.getAttribute("status")
    if(status === "desocupado" || status === ""){
        if ( confirm("ocupar vaga?") ){
            //placa do carro
            let placa = prompt("Placa do carro")

            //redirecionando para o php
            location.href = `estacionamento.php?acao=ocupar&vaga_ocupada=${vaga}&placa=${placa}`
        }
    } else {
        if ( confirm("desocupar vaga?") ){
            //redirecionando para o php
            location.href = `estacionamento.php?acao=desocupar&vaga_ocupada=${vaga}`
        }
    }
}