<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>S.I.G.A.M. - Pantalla de Turnos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(150deg, #BBFCC6, #BBFCF9) !important;
            min-height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Courier New', Courier, monospace;
        }
        .tv-container {
            background: white !important;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            width: 95%;
            height: 85vh; /* Ocupa casi toda la altura de la pantalla */
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        header {
            padding: 20px;
            text-align: center;
            border-bottom: 2px solid #8a91f3;
        }
        .main-content {
            display: flex;
            flex: 1; /* Ocupa el resto del espacio */
        }
        /* Lado Izquierdo: Próximos Turnos */
        .sidebar-espera {
            width: 30%;
            background: #f8f9fa;
            border-right: 2px solid #eee;
            padding: 20px;
            display: flex;
            flex-direction: column;
        }
        /* Lado Derecho: Turno Actual */
        .current-turn-area {
            width: 70%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }
        #turnoPrincipal {
            font-size: 15rem; /* Tamaño gigante para que se vea de lejos */
            font-weight: bold;
            line-height: 1;
            margin: 20px 0;
        }
        .label-grande {
            font-size: 2.5rem;
            color: #090909;
            font-weight: bold;
            text-transform: uppercase;
        }
        #listaEspera {
            list-style: none;
            padding: 0;
        }
        #listaEspera li {
            background: #8a91f3;
            color: white;
            padding: 15px;
            border-radius: 10px;
            font-size: 2.2rem;
            font-weight: bold;
            text-align: center;
            margin-bottom: 15px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>

    <div class="tv-container">
        <header>
            <h2 class="fw-bold m-0"><i class="fa-solid"></i> PANTALLA DE TURNOS</h2>
        </header>

        <div class="main-content">
            <div class="sidebar-espera">
                <h3 class="text-center fw-bold mb-4" style="color: #333; border-bottom: 1px solid #ddd; padding-bottom: 10px;">PRÓXIMOS</h3>
                <ul id="listaEspera">
                    </ul>
            </div>

            <div class="current-turn-area">
                <span class="label-grande ">Turno Actual</span>
                <div id="turnoPrincipal">SIN TURNOS</div>
                <div id="mensajePaciente" style="font-size: 2rem; color: #888;">Por favor, espere su llamado</div>
            </div>
        </div>
    </div>

    <script>
        function actualizarPantalla() {
            const turnosRaw = localStorage.getItem("turnos");
            const turnos = turnosRaw ? JSON.parse(turnosRaw) : [];
            
            const principal = document.getElementById("turnoPrincipal");
            const listaEspera = document.getElementById("listaEspera");

            if (turnos.length > 0) {
                // Turno actual (Derecha)
                let actual = turnos[0].split(" - ");
                principal.innerHTML = actual[0];
                principal.style.color = "#28a745"; // Verde éxito

                // Próximos turnos (Izquierda)
                listaEspera.innerHTML = "";
                let proximos = turnos.slice(1, 6); // Mostramos los siguientes 5
                
                if(proximos.length > 0) {
                    proximos.forEach(t => {
                        let li = document.createElement("li");
                        li.textContent = t.split(" - ")[0];
                        listaEspera.appendChild(li);
                    });
                } else {
                    listaEspera.innerHTML = "<p class='text-center text-muted'>No hay más turnos en espera</p>";
                }
            } else {
                principal.innerHTML = "---";
                principal.style.color = "#fe0505"; // Rojo
                listaEspera.innerHTML = "";
            }
        }

        setInterval(actualizarPantalla, 2000);
        window.onload = actualizarPantalla;
    </script>
</body>
</html>