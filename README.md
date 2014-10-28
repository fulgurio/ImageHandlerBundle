ImageHandlerBundle
==================

Image handler bundle use the very helpful [Vich uploader bundle](https://github.com/dustin10/VichUploaderBundle|), which allows file upload with annotations.

In the same way, Image handler adds in this bundle a way to handle uploaded picture, like resizing or cropping.

Here is an example of use, with the table field "avatar"

First, add the namespace for annotations :
``` php
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Fulgurio\ImageHandlerBundle\Annotation as ImageAnnotation;
```

Configure your entity class as explain in Vich Uploader :
``` php
/**
[...]
 *         @Vich\Uploadable
 */
```

Add a new class member, avatarFile
``` php
    /**
     * @var Symfony\Component\HttpFoundation\File\UploadedFile
     * @Vich\UploadableField(mapping="avatar_image" fileNameProperty="avatar")
     * @Assert\File(
     *     maxSize="1M",
     *     mimeTypes={"image/png", "image/jpeg", "image/pjpeg"}
     * )
     */
    private $avatarFile;
```
As you'll see, avatarFile is associated to avatar with fileNameProperty

We update the setAvarFile method avatar member, because if entity has no change, file will not be uploaded as well. So we "fake" a change by update ''avatar'' member.
``` php
    /**
     * Set avatarFile
     *
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $avatarFile
     */
    public function setAvatarFile(UploadedFile $avatarFile)
    {
        $this->avatarFile = $avatarFile;
        // Because we need an update of User object when form submit a file,
        // we make a fake update of $avatar
        if ($avatarFile)
        {
            $this->setAvatar(time());
        }
        return $this;
    }
```

At the end, you need to add image handler annotation to original field, avatar :
``` php
    /**
     * @ORM\Column(type="string", length=128, nullable=true)
     *
     * @ImageAnnotation\ImageHandle(mapping="avatar_image", action="resize", width=100, height=100)
     */
    private $avatar;
```
If you want to crop the picture, just replace the action by "crop", and change width of heigth as wanted.
You can type 3 fields for each function :
- width (optional): width to resize / crop
- height (optional): height to resize / crop
- mappping (optional): name of config mapping, to set width and height in global config. See above
- action (optional): by default, you'll resize the picture, but "crop" is also available


In app/config/config.yml, just type something like that :
``` yaml
fulgurio_image_handler:
    mappings:
        avatar_image:
            width: 400
            height: 400
        second_size:
            width: 200
            height: 200
            action: crop
```

With Vich/UploadBundle, you need to config a mapping name. In my case, it's in
@Vich\UploadableField(mapping="avatar_image" fileNameProperty="avatar").
If you put in you config file a fulgurio_image_handler.mappings.avatar_image, it
 will use this settings, so picture will be resize to 400x400.
But if you add a mapping settings in ImageHandler annotatin, like
@ImageAnnotation\ImageResize(mapping="second_size")
ImageHandler will crop to 200x200. Groovy !
