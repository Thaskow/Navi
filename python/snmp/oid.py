from snmp.snmp_client import *


def get_addrs_mac(target, community):
    # OID for mac address and port
    oidMac = '1.3.6.1.2.1.17.4.3.1.1'
    oidPort = '1.3.6.1.2.1.17.4.3.1.2'

    # Connect to snmp client
    snmp_client = SNMPClient(target, community)

    # Get mac address and port
    # responseMac = snmp_client.snmp_walk(oidMac)
    responsePort = snmp_client.snmp_walk(oidPort)

    value_counts = {}
    for item in responsePort:
        value = item[1]
        try:
            value_counts[value]
        except:
            value_counts[value] = {"count": "%", "oid": [item[0]]}
        if value_counts[value]["count"] == "%":
            value_counts[value]["count"] = 1

        else:
            value_counts[value]["count"] += 1
            value_counts[value]["oid"].append(item[0])

            # value_counts[value]["oid"] = item[0]
    result = {}
    for item in value_counts.items():
        value = item[0]
        print(value, value_counts[value]['count'])
        if value_counts[value]['count'] > 1:
            # Réduire la liste des interconnexion à 2 pour le débug
            # value_counts[value]['oid'] = value_counts[value]['oid'][:2]
            result[value] = ["I"]
            for oid in value_counts[value]['oid']:
                oidComplete = oidMac + '.' + oid[29:]
                result[value].append(snmp_client.snmp_get(oidComplete).replace('0x', ''))
        else:
            oidComplete = oidMac + '.' + value_counts[value]['oid'][0][29:]
            result[value] = snmp_client.snmp_get(oidComplete).replace('0x', '')

    return dict(sorted(result.items()))


def get_interfaces_infos(target, community):
    oidInterfaces = '1.3.6.1.2.1.2.2.1.8'
    oidInterfacesCount = '1.3.6.1.2.1.17.1.2.0'
    snmp_client = SNMPClient(target, community)
    responseInterfaces = snmp_client.snmp_walk(oidInterfaces)
    responseInterfacesCount = snmp_client.snmp_get(oidInterfacesCount)
    response = {}
    for i in range(0, int(responseInterfacesCount)):
        response[responseInterfaces[i][0].split('.')[-1]] = responseInterfaces[i][1]
    #     response[responseInterfaces[i][0].split('.')[-1]] = responseInterfaces[i][1]
    return response

def get_nbt_interfaces(target, community):
    oidInterfacesCount = '1.3.6.1.2.1.17.1.2.0'
    snmp_client = SNMPClient(target, community)
    return snmp_client.snmp_get(oidInterfacesCount)

