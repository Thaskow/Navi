from snmp import *
from db import *
from utils import *
from snmp import *
from datetime import datetime
import requests
import time


# switchs = LoadJson('../data/json/switchs.json')
#
# warnings = LoadJson('../data/json/warnings.json')
#
# white_list = LoadJson('../data/json/white_list.json')
def main():
    # SleepTime de 10 minutos
    sleepTime = 600
    # while True:
    switchs_json = LoadJson('data/json/switchs.json')
    switchs_data = switchs_json.load()

    ## switchs_data["timeLog"] is on minutes
    sleepTime = switchs_data["timeLog"] * 60

    # Add x days to today date
    date = datetime.now()
    date = date.replace(day=date.day - switchs_data["keepLog"])
    date = date.strftime("%Y-%m-%d %H:%M:%S")

    delete_old_logs(date)
    verify_switchs_ports()
    # time.sleep(sleepTime)


def delete_old_logs(cutoff_date):
    # Convertir cutoff_date en objet datetime
    cutoff_date = datetime.strptime(cutoff_date, "%Y-%m-%d %H:%M:%S")
    file_path = 'data/json/switchs.json'
    # Lire le fichier JSON
    with open(file_path, 'r') as file:
        data = json.load(file)

    # Filtrer l'historique pour chaque port
    for switch in data.get('switchs', []):
        for port in switch.get('ports', []):
            history = port.get('history', [])
            filtered_history = [
                entry for entry in history
                if datetime.strptime(entry['date'], "%Y-%m-%d %H:%M:%S") >= cutoff_date
            ]
            port['history'] = filtered_history

    # Écrire les modifications de retour dans le fichier JSON
    with open(file_path, 'w') as file:
        json.dump(data, file, indent=4)


def generate_warning_report(type, interconn, vendor, dte, status, mac, name, statusCheck, fromValidate, switch_name, switch_ip, switch_port):
    response = {
        "type": type,
        "interconnexion": interconn,
        "vendor": vendor,
        "date": dte,
        "status": status,
        "mac": mac,
        "name": name,
        "check": {
            "status": statusCheck,
            "base": fromValidate
        },
        "switch": {
            "name": switch_name,
            "ip": switch_ip,
            "port": switch_port
        }
    }
    return response


def generate_port_update_json(type, interconn, vendor, dte, status, mac, name, statusCheck, fromValidate):
    response = {
        "type": type,
        "interconnexion": interconn,
        "vendor": vendor,
        "date": dte,
        "status": status,
        "mac": mac,
        "name": name,
        "check": {
            "status": statusCheck,
            "base": fromValidate
        }
    }
    return response


def get_mac_vendor(mac_address):
    # Separate the mac address by '-' every two chart
    mac_address = ('-'.join([mac_address[i:i + 2] for i in range(0, len(mac_address), 2)])).upper()
    url = f"https://api.macvendors.com/{mac_address}"
    response = requests.get(url)
    if response.status_code == 200:
        # Return with first letter in uppercase
        return response.text[0].upper() + response.text[1:]
    else:
        return f"NC"

def in_warning_list(warning_new):
    warning_json = LoadJson('data/json/warning.json')
    warning_data = warning_json.load()
    dteWN = warning_new['date']
    # Comparer les dictionnaires en excluant la clé 'date'
    del warning_new['date']
    for w in warning_data['warnings']:
        wDt = w['date']
        del w['date']
        if warning_new == w:
            w['date'] = wDt
            warning_new['date'] = dteWN
            return True
        w['date'] = wDt
    warning_new['date'] = dteWN
    return False


def verify_switchs_ports():
    switchs = LoadJson('data/json/switchs.json')
    switchs_data = switchs.load()

    for switch in switchs_data['switchs']:
        interfaces = get_interfaces_infos(switch['ip'], switch['community'])
        macs = get_addrs_mac(switch['ip'], switch['community'])
        interfacesCount = get_nbt_interfaces(switch['ip'], switch['community'])
        # Je définis un compteur pour les interfaces
        u = 0
        # Je boucles sur les interfaces
        for i in interfaces:
            # J'ajoute 1 à mon compteur
            u += 1

            # Je vérifie si l'interface est déjà dans le fichier JSON sinon je l'ajoute
            try:
                switchs_data['switchs'][int(switchs_data['switchs'].index(switch))]['ports'][int(u)]
            except IndexError:
                switchs_data['switchs'][int(switchs_data['switchs'].index(switch))]['ports'].append(
                    {"numero": u, "history": []})

            try:
                previousValue = switchs_data['switchs'][int(switchs_data['switchs'].index(switch))]['ports'][int(u)-1]['history'][-1]
                previousMac = previousValue['mac']
            except IndexError:
                previousMac, previousValue = None, None


            # Si l'interface est active (à un mac de défini)
            if (i in macs):
                # Si l'interface est lié à un switch/routeur (interconnexion)
                if macs[i][0] == "I":
                    # Depop first element
                    macs[str(i)] = macs[i][1:]
                    if (len(macs[str(i)]) > 1 and len(macs[str(i)]) < switchs_data["maxPorts"]):
                        for mac in macs[str(i)]:
                            print(mac)
                            print(mac_in_wl(mac))
                            print(get_mac(mac))
                            if not (get_mac(mac) or mac_in_wl(mac) != [None, None]):
                                warning_json = LoadJson('data/json/warning.json')
                                warning_data = warning_json.load()
                                warning = generate_warning_report("Interconnexion", 1, get_mac_vendor(mac),
                                                                    datetime.now().strftime("%Y-%m-%d %H:%M:%S"),
                                                                    "UP", mac, None, "0", "Inconnu", switch['name'],switch['ip'], u)
                                # If warning already exist
                                if not in_warning_list(warning):
                                    warning_data['warnings'].append(warning)
                                    save_json_data(warning_data, 'data/json/warning.json')
                                    print("Warning", "Ajouté")
                                else:
                                    print("Warning", "Déjà existant")

                                print(warning)
                    print("Interconnexion")
                    update = generate_port_update_json(None, 1, None, datetime.now().strftime("%Y-%m-%d %H:%M:%S"),
                                                       "UP",
                                                       None,
                                                       None,
                                                       None, None)
                # Si c'est lié à un seul appareil
                else:
                    if macs[str(i)] == previousMac and previousMac != None:
                        previousValue['date'] = datetime.now().strftime("%Y-%m-%d %H:%M:%S")
                        update = previousValue
                    elif macs[str(i)]:
                        time.sleep(1)
                        if get_mac(macs[str(i)]):
                            update = generate_port_update_json("PC", None, get_mac_vendor(macs[str(i)]),
                                                               datetime.now().strftime("%Y-%m-%d %H:%M:%S"), "UP",
                                                               macs[str(i)], get_mac(macs[str(i)]), "1", "KS")
                        elif (mac_in_wl(macs[str(i)]) != [None, None]):

                            update = generate_port_update_json(mac_in_wl(macs[str(i)])[0], None, get_mac_vendor(macs[str(i)]),
                                                               datetime.now().strftime("%Y-%m-%d %H:%M:%S"), "UP",
                                                               macs[str(i)], mac_in_wl(macs[str(i)])[1], "1",
                                                               "White-List")

                        else:

                            # Error add new array in port switch value
                            vendor = get_mac_vendor(macs[str(i)])
                            update = generate_port_update_json("?", None, vendor,
                                                               datetime.now().strftime("%Y-%m-%d %H:%M:%S"), "UP",
                                                               macs[str(i)], "?", "0", "Inconnu")
                            warning_json = LoadJson('data/json/warning.json')
                            warning_data = warning_json.load()
                            warning_content = generate_warning_report("?", None, vendor,
                                                                      datetime.now().strftime("%Y-%m-%d %H:%M:%S"),
                                                                      "UP",
                                                                      macs[str(i)], "?", "0", "Inconnu", switch['name'], switch['ip'], u)
                            warning_data['warnings'].append(warning_content)
                            save_json_data(warning_data, 'data/json/warning.json')
            #Sinon si l'interface est inactive
            else:
                update = generate_port_update_json(None, None, None, datetime.now().strftime("%Y-%m-%d %H:%M:%S"),
                                                   "DOWN",
                                                   None,
                                                   None,
                                                   None, None)

            switchs_data['switchs'][int(switchs_data['switchs'].index(switch))]['ports'][int(u) - 1]['history'].append(
                update)
    save_json_data(switchs_data, 'data/json/switchs.json')


if __name__ == '__main__':
    main()
