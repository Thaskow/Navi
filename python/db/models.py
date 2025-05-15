from db.connection import KasperskyDB
import pandas as pd

def get_mac(mac):
    db = KasperskyDB()
    cnxn = db.connect()
    # Exemple de requête pour vérifier la connexion
    cnxn.execute("SELECT h.strWinHostName FROM hosts h INNER JOIN dbo.hst_mac m ON h.nId = m.nIdHost WHERE m.strMac = ?", mac)
    row = cnxn.fetchone()
    cnxn.close()
    try:
        # Si un résultat est trouvé, on retourne le nom de l'hôte
        return row[0]
    except:
        # Sinon, on retourne None
        return None

    # query = "SELECT * FROM hosts"
    # df = pd.read_sql(query, cnxn)
    # print(df.head())
    # cnxn.close()
