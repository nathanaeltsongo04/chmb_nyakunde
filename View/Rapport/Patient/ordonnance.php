<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Générateur d'ordonnance</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f0f4f8;
        }
        .container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 2rem;
            background-color: white;
            border-radius: 1rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        .form-section {
            display: grid;
            gap: 1.5rem;
            grid-template-columns: repeat(2, 1fr);
        }
        .full-width {
            grid-column: 1 / -1;
        }
        .prescription-print {
            display: none;
            padding: 2rem;
            border: 1px solid #ccc;
            border-radius: 0.5rem;
            margin-top: 2rem;
        }
        .prescription-print h2, .prescription-print h3 {
            text-align: center;
            font-weight: 600;
        }
        .prescription-print h3 {
            margin-bottom: 1rem;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
        }
        .info-row span {
            font-weight: 500;
        }
        @media print {
            body {
                background-color: white;
            }
            .container, .print-btn, .back-btn {
                display: none !important;
            }
            .prescription-print {
                display: block !important;
                border: none;
            }
        }
    </style>
</head>
<body class="p-4">

    <div id="app" class="container">
        <h1 class="text-3xl font-bold text-center mb-6 text-gray-800">Générateur d'ordonnance</h1>

        <!-- Formulaire de saisie -->
        <div id="inputForm">
            <h2 class="text-2xl font-semibold mb-4 text-gray-700">Informations de la prescription</h2>
            <div class="form-section">
                <!-- Médecin -->
                <div class="space-y-1">
                    <label for="doctorName" class="block text-sm font-medium text-gray-700">Nom du médecin</label>
                    <input type="text" id="doctorName" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2 border">
                </div>
                <div class="space-y-1">
                    <label for="doctorSpecialty" class="block text-sm font-medium text-gray-700">Spécialité</label>
                    <input type="text" id="doctorSpecialty" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2 border">
                </div>
                <div class="space-y-1">
                    <label for="doctorAddress" class="block text-sm font-medium text-gray-700">Adresse du cabinet</label>
                    <input type="text" id="doctorAddress" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2 border">
                </div>
                <div class="space-y-1">
                    <label for="doctorPhone" class="block text-sm font-medium text-gray-700">Téléphone</label>
                    <input type="tel" id="doctorPhone" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2 border">
                </div>

                <!-- Patient -->
                <div class="space-y-1">
                    <label for="patientName" class="block text-sm font-medium text-gray-700">Nom du patient</label>
                    <input type="text" id="patientName" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2 border">
                </div>
                <div class="space-y-1">
                    <label for="patientDob" class="block text-sm font-medium text-gray-700">Date de naissance</label>
                    <input type="date" id="patientDob" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2 border">
                </div>

                <!-- Médicament -->
                <div class="full-width space-y-1">
                    <label for="medicineName" class="block text-sm font-medium text-gray-700">Nom du médicament</label>
                    <input type="text" id="medicineName" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2 border">
                </div>
                <div class="space-y-1">
                    <label for="dosage" class="block text-sm font-medium text-gray-700">Dosage</label>
                    <input type="text" id="dosage" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2 border">
                </div>
                <div class="space-y-1">
                    <label for="duration" class="block text-sm font-medium text-gray-700">Durée</label>
                    <input type="text" id="duration" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2 border">
                </div>
                <div class="full-width space-y-1">
                    <label for="additionalNotes" class="block text-sm font-medium text-gray-700">Instructions supplémentaires</label>
                    <textarea id="additionalNotes" rows="3" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2 border"></textarea>
                </div>
            </div>

            <!-- Bouton de génération -->
            <div class="mt-6 flex justify-end">
                <button id="generateBtn" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-full shadow-lg transition duration-200">
                    Générer l'ordonnance
                </button>
            </div>
        </div>

        <!-- Section d'impression -->
        <div id="printSection" class="prescription-print">
            <h2 class="text-3xl font-bold mb-2">Ordonnance</h2>
            <hr class="border-t-2 border-gray-500 mb-4">

            <!-- Informations du médecin et du patient -->
            <div class="flex justify-between items-start mb-6">
                <div class="w-1/2">
                    <h3 class="text-xl font-bold mb-2">Dr. <span id="printDoctorName"></span></h3>
                    <p><span id="printDoctorSpecialty"></span></p>
                    <p><span id="printDoctorAddress"></span></p>
                    <p>Tél: <span id="printDoctorPhone"></span></p>
                </div>
                <div class="w-1/2 text-right">
                    <p>Date: <span id="printDate"></span></p>
                </div>
            </div>
            <div class="flex justify-between items-start mb-6">
                <div class="w-1/2">
                    <p class="font-bold">Patient:</p>
                    <p><span id="printPatientName"></span></p>
                    <p>Né le: <span id="printPatientDob"></span></p>
                </div>
            </div>
            
            <hr class="border-t-2 border-gray-500 mb-4">
            
            <!-- Détails de la prescription -->
            <h3 class="text-2xl font-bold mb-4">Prescription</h3>
            <div class="border border-gray-300 p-4 rounded-md">
                <p><span class="font-bold">Médicament:</span> <span id="printMedicineName"></span></p>
                <p><span class="font-bold">Dosage:</span> <span id="printDosage"></span></p>
                <p><span class="font-bold">Durée:</span> <span id="printDuration"></span></p>
                <p class="mt-4"><span class="font-bold">Instructions:</span></p>
                <p><span id="printAdditionalNotes"></span></p>
            </div>

            <div class="text-center mt-8">
                <p class="font-bold text-lg">Signature</p>
                <div class="w-48 h-20 border-b-2 border-gray-400 mx-auto mt-4"></div>
            </div>
        </div>

        <!-- Boutons d'action -->
        <div id="actionButtons" class="mt-6 flex justify-end gap-4 hidden">
            <button id="printBtn" class="print-btn bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-full shadow-lg transition duration-200">
                Imprimer
            </button>
            <button id="backBtn" class="back-btn bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-full shadow-lg transition duration-200">
                Retour
            </button>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const generateBtn = document.getElementById('generateBtn');
            const printBtn = document.getElementById('printBtn');
            const backBtn = document.getElementById('backBtn');
            const inputForm = document.getElementById('inputForm');
            const printSection = document.getElementById('printSection');
            const actionButtons = document.getElementById('actionButtons');

            // Fonction pour générer l'ordonnance
            generateBtn.addEventListener('click', () => {
                const doctorName = document.getElementById('doctorName').value;
                const doctorSpecialty = document.getElementById('doctorSpecialty').value;
                const doctorAddress = document.getElementById('doctorAddress').value;
                const doctorPhone = document.getElementById('doctorPhone').value;
                const patientName = document.getElementById('patientName').value;
                const patientDob = document.getElementById('patientDob').value;
                const medicineName = document.getElementById('medicineName').value;
                const dosage = document.getElementById('dosage').value;
                const duration = document.getElementById('duration').value;
                const additionalNotes = document.getElementById('additionalNotes').value;
                const prescriptionDate = new Date().toLocaleDateString('fr-FR', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric'
                });

                // Remplir la section d'impression
                document.getElementById('printDoctorName').textContent = doctorName;
                document.getElementById('printDoctorSpecialty').textContent = doctorSpecialty;
                document.getElementById('printDoctorAddress').textContent = doctorAddress;
                document.getElementById('printDoctorPhone').textContent = doctorPhone;
                document.getElementById('printPatientName').textContent = patientName;
                document.getElementById('printPatientDob').textContent = patientDob;
                document.getElementById('printMedicineName').textContent = medicineName;
                document.getElementById('printDosage').textContent = dosage;
                document.getElementById('printDuration').textContent = duration;
                document.getElementById('printAdditionalNotes').textContent = additionalNotes;
                document.getElementById('printDate').textContent = prescriptionDate;

                // Cacher le formulaire et montrer la section d'impression
                inputForm.style.display = 'none';
                printSection.style.display = 'block';
                actionButtons.style.display = 'flex';
            });

            // Fonction pour imprimer l'ordonnance
            printBtn.addEventListener('click', () => {
                window.print();
            });

            // Fonction pour revenir au formulaire
            backBtn.addEventListener('click', () => {
                inputForm.style.display = 'block';
                printSection.style.display = 'none';
                actionButtons.style.display = 'none';
            });
        });
    </script>
</body>
</html>
