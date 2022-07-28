import requests
from fake_useragent import UserAgent
import vars
import json

base_url = f"{vars.serverURI}/python/server.php"
# base_url = f"{vars.serverURI}/server.php"


def getLinks():
    headers = UserAgent().random
    # response = requests.get(
    #     f'{vars.serverURI}/python/server.php?get-links=1', headers={"Content-type": headers})
    response = requests.get(
        f'{base_url}?get-links=1', headers={"Content-type": headers})
    return response.json()


def saveOnServer(id=False, final='', newName=''):
    # data = {'set-link': 1, 'id': '3', 'url': '*This urontact with admin*',
    #         'fileID': '*This url is damagh admin*', 'isDriveId': 0, 'newName': '', 'message': ''}
    # directUrlToken = final
    # idDriveLink = 0
    # if len(directUrlToken.split('/file/d/')) > 1:
    #     directUrlToken = directUrlToken.split('/file/d/')[1]
    #     idDriveLink = 1
    #     if len(directUrlToken.split('/view')) > 1:
    #         directUrlToken = directUrlToken.split('/view')[0]

    # if id == False:
    #     return
    # message = ' '
    # if idDriveLink == 0:
    #     message = 'gdrive url is not fetched'
    query = f"?set-link=1&id={id}&url={final}"

    headers = UserAgent().random

    response = requests.post(
        f'{base_url}{query}', headers={"Content-type": headers})

    return response.text


if __name__ == '__main__':
    # print(getLinks())
    saveOnServer()
