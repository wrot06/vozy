<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reconocimiento de Voz</title>
    <style>
        body { font-family: sans-serif; padding: 2rem; }
        select, button { font-size: 1rem; margin-top: 1rem; }
        #texto { width: 100%; height: 150px; margin-top: 1rem; }
        #grabar {
            padding: 10px 20px;
            border: none;
            color: white;
            cursor: pointer;
        }
        .verde { background-color: green; }
        .rojo { background-color: red; }
    </style>
</head>
<body>    

    <label for="idioma">Selecciona el idioma:</label>
    <select id="idioma">
        <option value="es-ES">Español</option>
        <option value="en-US">Inglés</option>
        <option value="fr-FR">Francés</option>
        <option value="de-DE">Alemán</option>
    </select>

    <br>

    

    <textarea id="texto" placeholder="Aquí aparecerá el texto..."></textarea>

    <button id="grabar" class="verde">🎤 Grabar (F2-F9)</button>

    <script>
        const btn = document.getElementById('grabar');
        const output = document.getElementById('texto');
        const langSelect = document.getElementById('idioma');

        let recognition;
        let grabando = false;
        let controlManual = false; // true si se usa el botón, false si se usa F2/F9

        if ('webkitSpeechRecognition' in window) {
            recognition = new webkitSpeechRecognition();
            recognition.continuous = false;
            recognition.interimResults = false;
        } else {
            alert("Tu navegador no soporta reconocimiento de voz.");
        }

        recognition.onresult = (event) => {
            const resultado = event.results[0][0].transcript;
            output.value += resultado + '\n';
        };

        recognition.onerror = (event) => {
            console.log("Error: " + event.error);
            if (grabando && controlManual) recognition.start();
        };

        recognition.onend = () => {
            if (grabando && controlManual) recognition.start();
        };

        function iniciarGrabacion() {
            recognition.lang = langSelect.value;
            recognition.start();
            grabando = true;
            btn.textContent = "🔴 Grabando";
            btn.classList.remove("verde");
            btn.classList.add("rojo");
        }

        function detenerGrabacion() {
            grabando = false;
            recognition.stop();
            btn.textContent = "🎤 Grabar";
            btn.classList.remove("rojo");
            btn.classList.add("verde");
        }

        btn.addEventListener('click', () => {
            controlManual = true;
            grabando ? detenerGrabacion() : iniciarGrabacion();
        });

        // 🎹 Soporte para teclas F2 y F9 mientras están presionadas
        document.addEventListener('keydown', (e) => {
            if ((e.code === 'F2' || e.code === 'F9') && !grabando) {
                controlManual = false;
                iniciarGrabacion();
            }
        });

        document.addEventListener('keyup', (e) => {
            if ((e.code === 'F2' || e.code === 'F9') && grabando && !controlManual) {
                detenerGrabacion();
            }
        });
    </script>
</body>
</html>
