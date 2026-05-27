// =====================================================
// BUSCADOR CADETES
// =====================================================

function buscarCadete(){

    let input =
    document.getElementById("searchInput")
    .value.toLowerCase();

    let curso =
    document.getElementById("cursoFilter")
    .value.toLowerCase();

    let filas =
    document.querySelectorAll("#tablaCadetes tr");

    filas.forEach(fila => {

        let texto =
        fila.innerText.toLowerCase();

        let coincideBusqueda =
        texto.includes(input);

        let coincideCurso =
        curso === "" || texto.includes(curso);

        if(coincideBusqueda && coincideCurso){

            fila.style.display = "";

        }else{

            fila.style.display = "none";
        }

    });
}