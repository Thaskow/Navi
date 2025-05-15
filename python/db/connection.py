import pyodbc

class KasperskyDB:
    def __init__(self):
        self.server = "VM-KASPERSKY\\KAV_CS_ADMIN_KIT"
        self.database = "KAV"
        self.username = "KSAdminSQL"
        self.password = "KS@dmin!2022"

    def connect(self):
        # Chaîne de connexion
        conn_str = (
            f'DRIVER={{SQL Server Native Client 11.0}};'  # Driver ODBC pour SQL Server
            f'SERVER={self.server};'  # Serveur
            f'DATABASE={self.database};'  # Base de données
            f'UID={self.username};'  # Nom d'utilisateur
            f'PWD={self.password}'  # Mot de passe
        )

        try:
            # Établir la connexion
            conn = pyodbc.connect(conn_str)
            # print("Connection successful!")

            # Exemple de requête pour vérifier la connexion
            return conn.cursor()

        except pyodbc.Error as e:
            print(f"Connection failed: {e}")

