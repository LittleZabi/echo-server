from gDriveFile import createService
import os
try:
    CLIENT_SECRET_FILE = os.getcwd()+'\\renamer\\credentials.json'
except Exception as e:
    CLIENT_SECRET_FILE = 'credentials.json'
    print(f"[FINALREADY] {e}")
API_NAME = 'drive'
API_VERSION = 'v3'
SCOPES = ['https://www.googleapis.com/auth/drive.metadata.readonly',
          'https://www.googleapis.com/auth/drive']

global service
service = createService(CLIENT_SECRET_FILE, API_NAME, API_VERSION, SCOPES)


def copy_file_to_drive(file_to_copy_id='', folder_id='', copied_file_body=''):
    copied_file_obj = service.files().copy(
        fileId=file_to_copy_id, body=copied_file).execute()
    copied_file_file_id = copied_file_obj['id']
    print("infun1", copied_file_file_id)
    return True, copied_file_file_id


def get_Publink(file_id=''):
    ## MAKING FILE PUBLIC####
    request_body = {
        'role': 'reader',
        'type': 'anyone'
    }

    response_permission = service.permissions().create(
        fileId=file_id,
        body=request_body
    ).execute()

    ## Getting LINK OF FILE####

    response_share_link = service.files().get(
        fileId=file_id,
        fields='webViewLink'
    ).execute()
    print("InFunSction:get_Publink(_)::", response_share_link)

    return True, response_share_link['webViewLink']


file_to_copy_id = '1aMKTXRqGLJ9Xxp-0oRS2UuuCLTQoqOss'
file_to_copy_id = "13UVwqTY0LDygku8-fA0L5Qxt-gyhRihE"
copy_folder_id = '1cSRwzCcLUgCZm4b0yBLyyi6HxWnzdQL4'

r = os.getcwd() + '\\renamer\\rename.csv'
if os.path.exists(r) == False:
    r = '.\\rename.csv'
alllinkdata = []
try:
    f = open(r, "r")
    alllinkdata = f.readlines()
except Exception as e:
    print('[FINAL READY 58]', e)

with open(os.getcwd()+'\\renamer\\finalLinks.csv', "w") as file:
    file.write("")

for link_data in alllinkdata:
    try:
        temp_data = link_data.strip().split(",")
        fileLink_ = temp_data[0]
        fileName_ = temp_data[1]
        fileFolderId_ = temp_data[2]
        child_id = temp_data[3]

        print("DataRead:", fileLink_, fileName_, fileFolderId_)

        file_to_copy_id = fileLink_
        copy_file_name = fileName_
        copy_folder_id = fileFolderId_
        copied_file = {'title': 'COPYEDFILE',
                       'name': copy_file_name, 'parents': [copy_folder_id]}
        copy_file_to_drive_status, temp_id = copy_file_to_drive(
            file_to_copy_id=file_to_copy_id, folder_id=copy_folder_id, copied_file_body=copied_file)
        if copy_file_to_drive_status:
            get_Publink_status, temp_link = get_Publink(temp_id)
            if get_Publink_status:
                with open(os.getcwd()+'\\renamer\\finalLinks.csv', "a") as file:
                    file.write(f"{temp_link}, {child_id}\n")
                    print("Final Link Found::", temp_link, fileName_)

    except Exception as e:
        print(e)
        continue
